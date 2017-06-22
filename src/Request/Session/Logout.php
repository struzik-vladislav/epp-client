<?php

namespace Struzik\EPPClient\Request\Session;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Session\Logout as LogoutResponse;
use Struzik\EPPClient\Node\Session\Logout as LogoutNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;

/**
 * Object representation of the request of logout command.
 */
class Logout extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $logout = new LogoutNode($this);
        $command->append($logout);

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return LogoutResponse::class;
    }
}
