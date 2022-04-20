<?php

namespace Struzik\EPPClient\Request\Poll;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\PollNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Poll\PollResponse;

/**
 * Object representation of the request to the message queue with operation 'ack'.
 */
class PollAckRequest extends AbstractRequest
{
    private string $messageId = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        PollNode::create($this, $commandNode, PollNode::OPERATION_ACKNOWLEDGEMENT, $this->messageId);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return PollResponse::class;
    }

    /**
     * Setting the identifier of the message to dequeue. REQUIRED.
     *
     * @param string $messageId identifier of the message
     */
    public function setMessageId(string $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Getting the identifier of the message to dequeue.
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }
}
