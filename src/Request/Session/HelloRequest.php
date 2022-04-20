<?php

namespace Struzik\EPPClient\Request\Session;

use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Session\HelloNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Session\GreetingResponse;

class HelloRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        HelloNode::create($this, $eppNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return GreetingResponse::class;
    }
}
