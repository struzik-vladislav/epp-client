<?php

namespace Struzik\EPPClient\Request\Session;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Session\Greeting;
use Struzik\EPPClient\Node\Session\Hello as HelloNode;

/**
 * Object representation of the hello request.
 */
class Hello extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new HelloNode($this);
        $epp->append($command);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return Greeting::class;
    }
}
