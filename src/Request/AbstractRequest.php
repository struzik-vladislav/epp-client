<?php

namespace Struzik\EPPClient\Request;

use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\Exception\LogicException;
use Struzik\EPPClient\Extension\RequestAddonInterface;

/**
 * Basic implementation of the request object.
 */
abstract class AbstractRequest extends \DOMDocument implements RequestInterface
{
    /**
     * Instance of EPPClient.
     */
    private EPPClient $client;

    /**
     * Array of add-ons.
     */
    private array $addons;

    /**
     * Flag. The value is true if the request was built.
     */
    private bool $isBuilt;

    public function __construct(EPPClient $client)
    {
        parent::__construct('1.0', 'UTF-8');
        $this->xmlStandalone = false;
        $this->preserveWhiteSpace = true;
        $this->formatOutput = true;

        $this->client = $client;
        $this->addons = [];
        $this->isBuilt = false;
    }

    /**
     * Getting the EPP server client.
     */
    public function getClient(): EPPClient
    {
        return $this->client;
    }

    public function getDocument(): \DOMDocument
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): void
    {
        if ($this->isBuilt) {
            throw new LogicException("It's impossible to build an already built request.");
        }

        // Build command section
        $this->handleParameters();
        foreach ($this->addons as $addon) {
            $addon->build($this);
        }

        $this->isBuilt = true;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getResponseClass(): string;

    /**
     * {@inheritdoc}
     */
    public function addExtAddon(RequestAddonInterface $addon): void
    {
        $classname = get_class($addon);
        $this->addons[$classname] = $addon;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExtAddon(string $className): void
    {
        unset($this->addons[$className]);
    }

    /**
     * {@inheritdoc}
     */
    public function findExtAddon(string $className): ?RequestAddonInterface
    {
        return $this->addons[$className] ?? null;
    }

    /**
     * Handling the request object parameters and building XML document.
     */
    abstract protected function handleParameters(): void;
}
