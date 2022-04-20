<?php

namespace Struzik\EPPClient\Request\Poll;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\PollNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Poll\PollResponse;

/**
 * Object representation of the request to the message queue with operation 'req'.
 */
class PollReqRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        PollNode::create($this, $commandNode, PollNode::OPERATION_REQUEST);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return PollResponse::class;
    }
}
