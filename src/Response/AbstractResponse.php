<?php

namespace Struzik\EPPClient\Response;

use Struzik\EPPClient\Exception\RuntimeException;
use Struzik\EPPClient\NamespaceCollection;
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
     * @throws RuntimeException
     */
    public function __construct(string $xml, ?NamespaceCollection $namespaceCollection = null, ?NamespaceCollection $extNamespaceCollection = null)
    {
        try {
            parent::__construct();
            $this->preserveWhiteSpace = false;
            $this->loadXML($xml);
            $this->initDOMXPath($namespaceCollection, $extNamespaceCollection);
        } catch (\Throwable $e) {
            throw new RuntimeException('Error has occurred on building response object. See previous exception.', 0, $e);
        }
    }

    abstract public function isSuccess(): bool;

    public function get($xpathQuery, ?\DOMNode $contextNode = null): \DOMNodeList
    {
        return $this->xpath->query($xpathQuery, $contextNode);
    }

    public function getFirst(string $xpathQuery, ?\DOMNode $contextNode = null): ?\DOMNode
    {
        $list = $this->get($xpathQuery, $contextNode);

        return count($list) ? $list->item(0) : null;
    }

    public function getUsedNamespaces(): array
    {
        return $this->namespaces;
    }

    public function addExtAddon(object $addon): void
    {
        $classname = get_class($addon);
        $this->addons[$classname] = $addon;
    }

    public function removeExtAddon($classname): void
    {
        unset($this->addons[$classname]);
    }

    public function findExtAddon($classname): ?object
    {
        return $this->addons[$classname] ?? null;
    }

    /**
     * Initialisation XPath.
     *
     * @throws \Exception
     */
    private function initDOMXPath(?NamespaceCollection $namespaceCollection = null, ?NamespaceCollection $extNamespaceCollection = null): void
    {
        $this->xpath = new DOMXPath($this);

        if ($namespaceCollection) {
            foreach ($namespaceCollection as $name => $uri) {
                $this->namespaces[$name] = $uri;
                $this->xpath->registerNamespace($name, $uri);
            }
        }
        if ($extNamespaceCollection) {
            foreach ($extNamespaceCollection as $name => $uri) {
                $this->namespaces[$name] = $uri;
                $this->xpath->registerNamespace($name, $uri);
            }
        }

        $simpleXML = new \SimpleXMLElement($this->saveXML());
        $namespaces = $simpleXML->getNamespaces(true);

        foreach ($namespaces as $name => $uri) {
            if (in_array($uri, $this->namespaces, true)) {
                continue;
            }

            $name = $name ?: self::ROOT_NAMESPACE_NAME;
            $this->namespaces[$name] = $uri;
            $this->xpath->registerNamespace($name, $uri);
        }
    }
}
