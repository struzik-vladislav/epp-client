<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <clTRID> node.
 */
class TransactionId extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'clTRID');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        $identifier = $this->getRequest()
            ->getClient()
            ->getIdGenerator()
            ->generate($this->getRequest());
        $this->getNode()->nodeValue = $identifier;
    }
}
