<?php

namespace Struzik\EPPClient\Request\Poll;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Poll\Poll as PollResponse;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\Poll as PollNode;
use Struzik\EPPClient\Node\Common\TransactionId;

/**
 * Object representation of the request to the message queue with operation 'request'.
 */
class Request extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $poll = new PollNode($this, ['operation' => PollNode::OPERATION_REQUEST]);
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
}
