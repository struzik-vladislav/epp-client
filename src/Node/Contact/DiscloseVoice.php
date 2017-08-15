<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <contact:voice> node for disclosing information.
 */
class DiscloseVoice extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'contact:voice');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
    }
}
