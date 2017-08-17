<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <domain:authInfo> node.
 */
class AuthorizationInfo extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'domain:authInfo');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
    }
}
