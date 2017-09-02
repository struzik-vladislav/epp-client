<?php

namespace Struzik\EPPClient\Request\Poll;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Poll\Poll as PollResponse;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\Poll as PollNode;
use Struzik\EPPClient\Node\Common\TransactionId;

/**
 * Object representation of the request to the message queue with operation 'acknowledgement'.
 */
class Acknowledgement extends AbstractRequest
{
    /**
     * @var string
     */
    private $messageId;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $poll = new PollNode($this, [
            'operation' => PollNode::OPERATION_ACKNOWLEDGEMENT,
            'message-id' => $this->messageId,
        ]);
        $command->append($poll);

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return PollResponse::class;
    }

    /**
     * Setting the identifier of the message to dequeue. REQUIRED.
     *
     * @param string $messageId identifier of the message
     *
     * @return self
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Getting the identifier of the message to dequeue.
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }
}
