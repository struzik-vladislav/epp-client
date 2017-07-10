<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Node\Common\Delete as DeleteNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Contact\Delete as ContactDeleteNode;
use Struzik\EPPClient\Node\Contact\Identifier;
use Struzik\EPPClient\Response\Contact\Delete as DeleteResponse;

/**
 * Object representation of the request of contact deleting command.
 */
class Delete extends AbstractRequest
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $delete = new DeleteNode($this);
        $command->append($delete);

        $contactDelete = new ContactDeleteNode($this);
        $delete->append($contactDelete);

        $contactId = new Identifier($this, ['identifier' => $this->identifier]);
        $contactDelete->append($contactId);

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return DeleteResponse::class;
    }

    /**
     * Getting the identifier of the contact.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Setting the identifier of the contact.
     *
     * @param string $identifier contact identifier
     *
     * @return self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }
}
