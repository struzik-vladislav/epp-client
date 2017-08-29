<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\Transfer as TransferResponse;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\Transfer as TransferNode;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Contact\Transfer;
use Struzik\EPPClient\Node\Contact\Password;
use Struzik\EPPClient\Node\Contact\Identifier;
use Struzik\EPPClient\Node\Contact\AuthorizationInfo;

/**
 * Object representation of the request of the contact transferring with operation 'query'.
 */
class TransferQuery extends AbstractRequest
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $password;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $transfer = new TransferNode($this, ['operation' => TransferNode::OPERATION_QUERY]);
        $command->append($transfer);

        $contactTransfer = new Transfer($this);
        $transfer->append($contactTransfer);

        $contactId = new Identifier($this, ['identifier' => $this->identifier]);
        $contactTransfer->append($contactId);

        if ($this->password !== null) {
            $contactAuthinfo = new AuthorizationInfo($this);
            $contactTransfer->append($contactAuthinfo);

            $contactPassword = new Password($this, ['password' => $this->password]);
            $contactAuthinfo->append($contactPassword);
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return TransferResponse::class;
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
     * Setting the password.
     *
     * @param string|null $password associated authorization information
     *
     * @return self
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }
}
