<?php

namespace Struzik\EPPClient\Request;

use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\Node\Common\Epp;
use Struzik\EPPClient\Node\Common\Extension;
use Struzik\EPPClient\Exception\LogicException;
use Struzik\EPPClient\Extension\RequestAddonInterface;

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

    /**
     * Array of add-ons.
     *
     * @var array
     */
    private $addons = [];

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

        // Build command section
        $this->handleParameters();
        $this->root->build();
        $this->appendChild($this->root->getNode());

        // Build extension section
        if (count($this->addons) > 0) {
            $nodeList = $this->getElementsByTagName('command');
            if ($nodeList->length === 0) {
                throw new LogicException('Add-on can be added in the request with \'command\' element.');
            }
            $command = $nodeList->item(0);

            $extension = new Extension($this);
            foreach ($this->addons as $addon) {
                $addon->build($this);
                $extension->getNode()->appendChild($addon->getRoot());
            }

            $nodeList = $this->getElementsByTagName('clTRID');
            $clTRID = $nodeList->length > 0 ? $nodeList->item(0) : null;

            $command->insertBefore($extension->getNode(), $clTRID);
        }

        $this->isBuilt = true;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getResponseClass();

    /**
     * {@inheritdoc}
     */
    public function addExtAddon(RequestAddonInterface $addon)
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
     * Handling the request object parameters and building a hierarchy of nodes.
     */
    abstract protected function handleParameters();
}
