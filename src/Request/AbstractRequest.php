<?php

namespace Struzik\EPPClient\Request;

use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\Node\Common\Epp;
use Struzik\EPPClient\Exception\LogicException;

/**
 * Basic implementation of the request object.
 */
abstract class AbstractRequest extends \DOMDocument implements RequestInterface
{
    /**
     * Instance of EPPClient.
     *
     * @var EPPClient
     */
    private $client;

    /**
     * Root node of XML document.
     *
     * @var Epp
     */
    private $root;

    /**
     * Flag. The value is true if the request was built.
     *
     * @var bool
     */
    private $isBuilt;

    public function __construct(EPPClient $client)
    {
        parent::__construct('1.0', 'UTF-8');
        $this->xmlStandalone = false;
        $this->preserveWhiteSpace = true;
        $this->formatOutput = true;
        $this->isBuilt = false;

        $this->client = $client;
        $this->root = new Epp($this);
    }

    /**
     * Getting the EPP server client.
     *
     * @return EPPClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Getting the root node of the request object.
     *
     * @return Epp
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    public function isBuilt()
    {
        return $this->isBuilt;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if ($this->isBuilt()) {
            throw new LogicException('It\'s impossible to build an already built request.');
        }

        $this->handleParameters();
        $this->root->build();
        $this->appendChild($this->root->getNode());
        $this->isBuilt = true;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getResponseClass();

    /**
     * Handling the request object parameters and building a hierarchy of nodes.
     */
    abstract protected function handleParameters();
}
