<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Common\TransferNode;
use Struzik\EPPClient\Node\Contact\ContactAuthInfoNode;
use Struzik\EPPClient\Node\Contact\ContactIdentifierNode;
use Struzik\EPPClient\Node\Contact\ContactPasswordNode;
use Struzik\EPPClient\Node\Contact\ContactTransferNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\TransferContactResponse;

/**
 * Object representation of the request of the contact transferring with operation 'cancel'.
 */
class CancelContactTransferRequest extends AbstractRequest
{
    private string $identifier = '';
    private string $password = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $transferNode = TransferNode::create($this, $commandNode, TransferNode::OPERATION_CANCEL);
        $contactTransferNode = ContactTransferNode::create($this, $transferNode);
        ContactIdentifierNode::create($this, $contactTransferNode, $this->identifier);
        $contactAuthInfoNode = ContactAuthInfoNode::create($this, $contactTransferNode);
        ContactPasswordNode::create($this, $contactAuthInfoNode, $this->password);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return TransferContactResponse::class;
    }

    /**
     * Setting the identifier of the contact. REQUIRED.
     *
     * @param string $identifier contact identifier
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Getting the identifier of the contact.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Setting the password. REQUIRED.
     *
     * @param string $password associated authorization information
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
