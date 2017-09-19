<?php

namespace Struzik\EPPClient;

use Struzik\EPPClient\Connection\ConnectionInterface;
use Struzik\EPPClient\Exception\LogicException;
use Struzik\EPPClient\Exception\ConnectionException;
use Struzik\EPPClient\Response\Session\Greeting;
use Struzik\EPPClient\Response\ResponseInterface;
use Struzik\EPPClient\IdGenerator\BasicGenerator;
use Struzik\EPPClient\IdGenerator\IdGeneratorInterface;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Extension\ExtensionInterface;
use Psr\Log\LoggerInterface;

class EPPClient
{
    /**
     * Connection to the EPP server.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Logger object.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Collection of object namespaces.
     *
     * @var NamespaceCollection
     */
    private $namespaceCollection;

    /**
     * Collection of extension namespaces.
     *
     * @var NamespaceCollection
     */
    private $extNamespaceCollection;

    /**
     * Identifier generator for client's transaction.
     *
     * @var IdGeneratorInterface
     */
    private $idGenerator;

    /**
     * Stack of EPP-extension.
     *
     * @var ExtensionInterface[]
     */
    private $extensionStack;

    /**
     * @param ConnectionInterface $connection instance of connection object
     * @param LoggerInterface     $logger     instance of logger object
     */
    public function __construct(ConnectionInterface $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
        $this->namespaceCollection = new NamespaceCollection();
        $this->extNamespaceCollection = new NamespaceCollection();
        $this->idGenerator = new BasicGenerator();
        $this->extensionStack = array();
    }

    /**
     * Opening the connection to the EPP server.
     *
     * @return Greeting
     *
     * @throws ConnectionException
     * @throws LogicException
     */
    public function connect()
    {
        if ($this->connection->isOpened()) {
            throw new LogicException('It is not possible to re-open the connection to the EPP server.');
        }

        $this->connection->open();
        $xml = $this->connection->read();
        $this->logger->info('Greeting on connection to the EPP server.', ['body' => $xml]);

        $greeting = new Greeting($xml);
        if (!$greeting->isSuccess()) {
            throw new ConnectionException('Invalid greeting on connection to the EPP server.');
        }

        return $greeting;
    }

    /**
     * Send EPP request.
     *
     * @param RequestInterface $request EPP request
     *
     * @return ResponseInterface
     *
     * @throws LogicException
     */
    public function send(RequestInterface $request)
    {
        // Checking the connection
        if (!$this->connection->isOpened()) {
            throw new LogicException('Cannot send request to the not open connection. Call connect() before send().');
        }

        // Preparing the request
        if (!$request->isBuilt()) {
            $request->build();
        }

        $requestXML = $request->saveXML();
        $this->logger->info('EPP request body.', ['body' => $requestXML]);

        // Sending a request
        $this->connection->write($requestXML);
        $responseXML = $this->connection->read();
        $this->logger->info('EPP response body.', ['body' => $responseXML]);

        // Creating a response
        $responseClass = $request->getResponseClass();
        $response = new $responseClass($responseXML);

        // Handle response in the extension
        foreach ($this->extensionStack as $extension) {
            $this->logger->debug(sprintf('Handle response in \'%s\' extension.', get_class($extension)));
            $extension->handleResponse($response);
        }

        return $response;
    }

    /**
     * Getting the URI collection of objects.
     *
     * @return NamespaceCollection
     */
    public function getNamespaceCollection()
    {
        return $this->namespaceCollection;
    }

    /**
     * Setting the URI collection of objects.
     *
     * @param NamespaceCollection $collection Collection object
     */
    public function setNamespaceCollection(NamespaceCollection $collection)
    {
        $this->namespaceCollection = $collection;

        return $this;
    }

    /**
     * Getting the URI collection of extensions.
     *
     * @return NamespaceCollection
     */
    public function getExtNamespaceCollection()
    {
        return $this->extNamespaceCollection;
    }

    /**
     * Setting the URI collection of extensions.
     *
     * @param NamespaceCollection $collection Collection object
     */
    public function setExtNamespaceCollection(NamespaceCollection $collection)
    {
        $this->extNamespaceCollection = $collection;

        return $this;
    }

    /**
     * Getting the identifier generator.
     *
     * @return IdGeneratorInterface
     */
    public function getIdGenerator()
    {
        return $this->idGenerator;
    }

    /**
     * Setting the identifier generator.
     *
     * @param IdGeneratorInterface $idGenerator Generator object
     */
    public function setIdGenerator(IdGeneratorInterface $idGenerator)
    {
        $this->idGenerator = $idGenerator;

        return $this;
    }

    /**
     * Add extension in stack.
     *
     * @param ExtensionInterface $extension instance of extension
     *
     * @return self
     */
    public function pushExtension(ExtensionInterface $extension)
    {
        array_unshift($this->extensionStack, $extension);
        $extension->setupNamespaces($this);

        return $this;
    }

    /**
     * Retrieving extension from the stack.
     *
     * @return ExtensionInterface
     *
     * @throws LogicException
     */
    public function popExtension()
    {
        if (!$this->extensionStack) {
            throw new LogicException('You tried to pop from an empty extension stack.');
        }

        return array_shift($this->extensionStack);
    }
}
