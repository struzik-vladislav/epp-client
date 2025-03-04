<?php

namespace Struzik\EPPClient;

use Psr\Log\LoggerInterface;
use Struzik\EPPClient\Connection\ConnectionInterface;
use Struzik\EPPClient\Exception\ConnectionException;
use Struzik\EPPClient\Exception\LogicException;
use Struzik\EPPClient\Extension\ExtensionInterface;
use Struzik\EPPClient\IdGenerator\BasicGenerator;
use Struzik\EPPClient\IdGenerator\IdGeneratorInterface;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Response\ResponseInterface;

class EPPClient
{
    private ConnectionInterface $connection;
    private LoggerInterface $logger;
    private NamespaceCollection $namespaceCollection;
    private NamespaceCollection $extNamespaceCollection;
    private IdGeneratorInterface $idGenerator;
    /** @var ExtensionInterface[] */
    private array $extensionStack;

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
        $this->extensionStack = [];
    }

    /**
     * Opening the connection to the EPP server.
     *
     * @throws ConnectionException
     * @throws LogicException
     */
    public function connect(): void
    {
        if ($this->connection->isOpened()) {
            throw new LogicException('It is not possible to re-open the connection to the EPP server.');
        }

        $this->connection->open();
    }

    /**
     * Closing the connection to the EPP server.
     *
     * @throws ConnectionException
     */
    public function disconnect(): void
    {
        $this->connection->close();
    }

    /**
     * Send EPP request.
     *
     * @param RequestInterface $request EPP request
     *
     * @throws LogicException
     * @throws ConnectionException
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        // Checking the connection
        if (!$this->connection->isOpened()) {
            throw new LogicException('Cannot send request to the not open connection. Call connect() before send().');
        }

        // Preparing the request
        $request->build();
        $requestXML = $request->getDocument()->saveXML();

        // Sending a request
        $this->connection->write($requestXML);
        $responseXML = $this->connection->read();

        // Creating a response
        $responseClass = $request->getResponseClass();
        $response = new $responseClass($responseXML, $this->getNamespaceCollection(), $this->getExtNamespaceCollection());

        // Handle response in the extension
        foreach ($this->extensionStack as $extension) {
            $this->logger->debug(sprintf('Handle response in \'%s\' extension.', get_class($extension)));
            $extension->handleResponse($response);
        }

        return $response;
    }

    /**
     * Getting the connection object.
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * Getting the URI collection of objects.
     */
    public function getNamespaceCollection(): NamespaceCollection
    {
        return $this->namespaceCollection;
    }

    /**
     * Setting the URI collection of objects.
     *
     * @param NamespaceCollection $collection Collection object
     */
    public function setNamespaceCollection(NamespaceCollection $collection): void
    {
        $this->namespaceCollection = $collection;
    }

    /**
     * Getting the URI collection of extensions.
     */
    public function getExtNamespaceCollection(): NamespaceCollection
    {
        return $this->extNamespaceCollection;
    }

    /**
     * Setting the URI collection of extensions.
     *
     * @param NamespaceCollection $collection Collection object
     */
    public function setExtNamespaceCollection(NamespaceCollection $collection): void
    {
        $this->extNamespaceCollection = $collection;
    }

    /**
     * Getting the identifier generator.
     */
    public function getIdGenerator(): IdGeneratorInterface
    {
        return $this->idGenerator;
    }

    /**
     * Setting the identifier generator.
     *
     * @param IdGeneratorInterface $idGenerator Generator object
     */
    public function setIdGenerator(IdGeneratorInterface $idGenerator): void
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * Add extension in stack.
     *
     * @param ExtensionInterface $extension instance of extension
     */
    public function pushExtension(ExtensionInterface $extension): void
    {
        array_unshift($this->extensionStack, $extension);
        $extension->setupNamespaces($this);
    }

    /**
     * Retrieving extension from the stack.
     *
     * @throws LogicException
     */
    public function popExtension(): ExtensionInterface
    {
        if (!$this->extensionStack) {
            throw new LogicException('You tried to pop from an empty extension stack.');
        }

        return array_shift($this->extensionStack);
    }
}
