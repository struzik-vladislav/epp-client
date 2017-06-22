<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <svcExtension> node.
 */
class ExtNamespaces extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'svcExtension');
    }

    /**
     * {@inheritdoc}
     */
    public function handleParameters($parameters = [])
    {
    }
}
