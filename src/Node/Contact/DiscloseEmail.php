<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <contact:email> node for disclosing information.
 */
class DiscloseEmail extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'contact:email');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
    }
}
