<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <contact:fax> node for disclosing information.
 */
class DiscloseFax extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'contact:fax');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
    }
}
