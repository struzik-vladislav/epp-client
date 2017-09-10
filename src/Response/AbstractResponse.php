<?php

namespace Struzik\EPPClient\Response;

use XPath\DOMXPath;
use Struzik\ErrorHandler\ErrorHandler;
use Struzik\ErrorHandler\Processor\IntoExceptionProcessor;
use Struzik\ErrorHandler\Exception\ErrorException;
use Struzik\EPPClient\Exception\RuntimeException;

/**
 * Basic implementation of the response object.
 */
abstract class AbstractResponse extends \DomDocument implements ResponseInterface
{
    /**
     * XPath query handler.
     *
     * @var \DOMXPath
     */
    public $xpath;

    /**
     * Namespaces used in document.
     *
     * @var array
     */
    private $namespaces = [];

    /**
     * Array of add-ons.
     *
     * @var array
     */
    private $addons = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($xml)
    {
        try {
            $errorHandler = (new ErrorHandler())
                ->pushProcessor((new IntoExceptionProcessor())->setErrorTypes(E_ALL));
            $errorHandler->set();

            $this->preserveWhiteSpace = false;
            $this->loadXML($xml);
            $this->initDOMXPath();

            $errorHandler->restore();
        } catch (ErrorException $e) {
            throw new RuntimeException('Error has occurred on building response object. See previous exception.', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    abstract public function isSuccess();

    /**
     * {@inheritdoc}
     */
    public function get($xpathQuery, \DOMNode $contextnode = null)
    {
        return $this->xpath->query($xpathQuery, $contextnode);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirst($xpathQuery, \DOMNode $contextnode = null)
    {
        $list = $this->get($xpathQuery, $contextnode);

        return count($list) ? $list->item(0) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsedNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtAddon($addon)
    {
        $classname = get_class($addon);
        $this->addons[$classname] = $addon;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExtAddon($classname)
    {
        if (isset($this->addons[$classname])) {
            unset($this->addons[$classname]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findExtAddon($classname)
    {
        return isset($this->addons[$classname]) ? $this->addons[$classname] : null;
    }

    /**
     * Initialisation XPath.
     *
     * @return $this
     */
    private function initDOMXPath()
    {
        $this->xpath = new DOMXPath($this);

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
