<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\Check as CheckResponse;
use Struzik\EPPClient\Node\Common\Check as CheckNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Contact\Check as ContactCheckNode;
use Struzik\EPPClient\Node\Contact\Identifier;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the request of contact checking command.
 */
class Check extends AbstractRequest
{
    /**
     * @var array
     */
    private $identifiers = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $check = new CheckNode($this);
        $command->append($check);

        $contactCheck = new ContactCheckNode($this);
        $check->append($contactCheck);

        if (count($this->identifiers) == 0) {
            throw new UnexpectedValueException('List of identifiers to be checking cannot be empty.');
        }

        foreach ($this->identifiers as $item) {
            $contactId = new Identifier($this, ['identifier' => $item]);
            $contactCheck->append($contactId);
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return CheckResponse::class;
    }

    /**
     * Adding a identifier to the list.
     *
     * @param string $identifier contact identifier
     *
     * @return self
     */
    public function addIdentifier($identifier)
    {
        if (!isset($this->identifiers[$identifier])) {
            $this->identifiers[$identifier] = $identifier;
        }

        return $this;
    }

    /**
     * Removing a identifier from the list.
     *
     * @param string $identifier contact identifier
     *
     * @return self
     */
    public function removeIdentifier($identifier)
    {
        if (isset($this->identifiers[$identifier])) {
            unset($this->identifiers[$identifier]);
        }

        return $this;
    }
}
