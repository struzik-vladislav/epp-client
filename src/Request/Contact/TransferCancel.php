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
 * Object representation of the request of the contact transferring with operation 'cancel'.
 */
class TransferCancel extends AbstractRequest
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

        $transfer = new TransferNode($this, ['operation' => TransferNode::OPERATION_CANCEL]);
        $command->append($transfer);

        $contactTransfer = new Transfer($this);
        $transfer->append($contactTransfer);

        $contactId = new Identifier($this, ['identifier' => $this->identifier]);
        $contactTransfer->append($contactId);

        $contactAuthinfo = new AuthorizationInfo($this);
        $contactTransfer->append($contactAuthinfo);

        $contactPassword = new Password($this, ['password' => $this->password]);
        $contactAuthinfo->append($contactPassword);

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
     * Setting the identifier of the contact. REQUIRED.
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
     * Setting the password. REQUIRED.
     *
     * @param string $password associated authorization information
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
