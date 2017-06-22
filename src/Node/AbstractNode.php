<?php

namespace Struzik\EPPClient\Node;

use Struzik\EPPClient\Request\RequestInterface;

/**
 * Basic implementation of node.
 */
abstract class AbstractNode
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \DOMElement
     */
    private $node;

    /**
     * @var self[]
     */
    private $child = [];

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param string           $name       The tag name of the element
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $name, $parameters = [])
    {
        $this->request = $request;
        $this->node = $this->getRequest()->createElement($name);
        $this->handleParameters($parameters);
    }

    /**
     * Getting the request object to which the node belongs.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Getting the original DOMElement object.
     *
     * @return \DOMElement
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Adding a node as a child to the current node.
     *
     * @param AbstractNode $element Child node
     *
     * @return self
     */
    public function append(AbstractNode $element)
    {
        $this->child[] = $element;

        return $this;
    }

    /**
     * Building a request for sending.
     *
     * @return self
     */
    public function build()
    {
        foreach ($this->child as $element) {
            $element->build();
            $this->node->appendChild($element->getNode());
        }

        return $this;
    }

    /**
     * Processing the parameters required to make a node.
     *
     * @param array $parameters Array of parameters
     */
    abstract protected function handleParameters($parameters = []);
}
