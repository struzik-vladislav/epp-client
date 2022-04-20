<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Common\CheckNode;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Contact\ContactCheckNode;
use Struzik\EPPClient\Node\Contact\ContactIdentifierNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\CheckContactResponse;

/**
 * Object representation of the request of contact checking command.
 */
class CheckContactRequest extends AbstractRequest
{
    private array $identifiers = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        if (count($this->identifiers) === 0) {
            throw new UnexpectedValueException('List of identifiers to be checking cannot be empty.');
        }

        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $checkNode = CheckNode::create($this, $commandNode);
        $contactCheckNode = ContactCheckNode::create($this, $checkNode);
        foreach ($this->identifiers as $item) {
            ContactIdentifierNode::create($this, $contactCheckNode, $item);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return CheckContactResponse::class;
    }

    /**
     * Adding identifier to the list.
     *
     * @param string $identifier contact identifier
     */
    public function addIdentifier(string $identifier): self
    {
        $this->identifiers[$identifier] = $identifier;

        return $this;
    }

    /**
     * Removing identifier from the list.
     *
     * @param string $identifier contact identifier
     */
    public function removeIdentifier(string $identifier): self
    {
        unset($this->identifiers[$identifier]);

        return $this;
    }
}
