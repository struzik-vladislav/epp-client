<?php

namespace Struzik\EPPClient\Request\Session;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Session\LogoutNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Session\LogoutResponse;

class LogoutRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        LogoutNode::create($this, $commandNode);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return LogoutResponse::class;
    }
}
