<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <hello> node.
 */
class Hello extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'hello');
    }

    /**
     * {@inheritdoc}
     */
    public function handleParameters($parameters = [])
    {
    }
}
