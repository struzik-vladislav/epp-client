<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\InfoNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Contact\ContactAuthInfoNode;
use Struzik\EPPClient\Node\Contact\ContactIdentifierNode;
use Struzik\EPPClient\Node\Contact\ContactInfoNode;
use Struzik\EPPClient\Node\Contact\ContactPasswordNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\InfoContactResponse;

/**
 * Object representation of the request of contact information command.
 */
class InfoContactRequest extends AbstractRequest
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
        $infoNode = InfoNode::create($this, $commandNode);
        $contactInfoNode = ContactInfoNode::create($this, $infoNode);
        ContactIdentifierNode::create($this, $contactInfoNode, $this->identifier);
        if ($this->password !== '') {
            $contactAuthInfoNode = ContactAuthInfoNode::create($this, $contactInfoNode);
            ContactPasswordNode::create($this, $contactAuthInfoNode, $this->password);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return InfoContactResponse::class;
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
     * Setting the password of the contact. OPTIONAL.
     *
     * @param string $password authorization information associated with the contact object
     */
    public function setPassword(string $password = ''): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the contact.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
