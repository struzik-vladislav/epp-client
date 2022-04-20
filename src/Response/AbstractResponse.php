<?php

namespace Struzik\EPPClient\Response;

use Struzik\EPPClient\Exception\RuntimeException;
use XPath\DOMXPath;

/**
 * Basic implementation of the response object.
 */
abstract class AbstractResponse extends \DomDocument implements ResponseInterface
{
    /**
     * XPath query handler.
     */
    public \DOMXPath $xpath;

    /**
     * Namespaces used in document.
     */
    private array $namespaces = [];

    /**
     * Array of add-ons.
     */
    private array $addons = [];

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function __construct(string $xml)
    {
        try {
            parent::__construct();
            $this->preserveWhiteSpace = false;
            $this->loadXML($xml);
            $this->initDOMXPath();
        } catch (\Throwable $e) {
            throw new RuntimeException('Error has occurred on building response object. See previous exception.', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    abstract public function isSuccess(): bool;

    /**
     * {@inheritdoc}
     */
    public function get($xpathQuery, \DOMNode $contextNode = null): \DOMNodeList
    {
        return $this->xpath->query($xpathQuery, $contextNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirst(string $xpathQuery, \DOMNode $contextNode = null): ?\DOMNode
    {
        $list = $this->get($xpathQuery, $contextNode);

        return count($list) ? $list->item(0) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsedNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtAddon(object $addon): void
    {
        $classname = get_class($addon);
        $this->addons[$classname] = $addon;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExtAddon($classname): void
    {
        unset($this->addons[$classname]);
    }

    /**
     * {@inheritdoc}
     */
    public function findExtAddon($classname): ?object
    {
        return $this->addons[$classname] ?? null;
    }

    /**
     * Initialisation XPath.
     *
     * @throws \Exception
     */
    private function initDOMXPath(): void
    {
        $this->xpath = new DOMXPath($this);

        $simpleXML = new \SimpleXMLElement($this->saveXML());
        $namespaces = $simpleXML->getNamespaces(true);

        foreach ($namespaces as $name => $uri) {
            $name = $name ?: self::ROOT_NAMESPACE_NAME;
            $this->namespaces[$name] = $uri;
            $this->xpath->registerNamespace($name, $uri);
        }
    }
}
