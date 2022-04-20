<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\DeleteNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Contact\ContactDeleteNode;
use Struzik\EPPClient\Node\Contact\ContactIdentifierNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\DeleteContactResponse;

/**
 * Object representation of the request of contact deleting command.
 */
class DeleteContactRequest extends AbstractRequest
{
    private string $identifier = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $deleteNode = DeleteNode::create($this, $commandNode);
        $contactDeleteNode = ContactDeleteNode::create($this, $deleteNode);
        ContactIdentifierNode::create($this, $contactDeleteNode, $this->identifier);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return DeleteContactResponse::class;
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
}
