<?php

namespace Struzik\EPPClient\Response;

abstract class AbstractResponse extends \DomDocument implements ResponseInterface
{
    /** @var \DOMXPath */
    public $xpath;

    /** @var array Namespaces used in document */
    private $namespaces = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($xml)
    {
        $this->preserveWhiteSpace = false;
        $this->loadXML($xml);
        $this->initDOMXPath();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function isSuccess();

    /**
     * {@inheritdoc}
     */
    public function get($xpathQuery)
    {
        return $this->xpath->query($xpathQuery);
    }

    /**
     * Evaluates the given XPath expression and return the first element from DOMNodeList or null.
     *
     * @param  string
     *
     * @return \DOMNode|null
     */
    public function getFirst($xpathQuery)
    {
        $list = $this->get($xpathQuery);

        return count($list) ? $list->item(0) : null;
    }

    /**
     * Returns namespaces used in document.
     *
     * @return array Array of namespace names with their associated URIs
     */
    public function getUsedNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Initialisation XPath.
     *
     * @return $this
     */
    private function initDOMXPath()
    {
        $this->xpath = new \DOMXPath($this);
        $simpleXML = new \SimpleXMLElement($this->saveXML());
        $namespaces = $simpleXML->getNamespaces(true);

        foreach ($namespaces as $name => $uri) {
            $name = $name ?: self::ROOT_NAMESPACE_NAME;
            $this->namespaces[$name] = $uri;
            $this->xpath->registerNamespace($name, $uri);
        }

        return $this;
    }
}
