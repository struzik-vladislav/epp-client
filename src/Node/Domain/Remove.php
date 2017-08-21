<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <domain:rem> node.
 */
class Remove extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'domain:rem');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
    }
}
