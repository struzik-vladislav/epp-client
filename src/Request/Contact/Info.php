<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\Info as InfoResponse;
use Struzik\EPPClient\Node\Common\Info as InfoNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Contact\Info as ContactInfoNode;
use Struzik\EPPClient\Node\Contact\Identifier;
use Struzik\EPPClient\Node\Contact\AuthorizationInfo;
use Struzik\EPPClient\Node\Session\Password;

/**
 * Object representation of the request of contact information command.
 */
class Info extends AbstractRequest
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

        $info = new InfoNode($this);
        $command->append($info);

        $contactInfo = new ContactInfoNode($this);
        $info->append($contactInfo);

        $contactId = new Identifier($this, ['identifier' => $this->identifier]);
        $contactInfo->append($contactId);

        if ($this->password !== null) {
            $contactAuthorization = new AuthorizationInfo($this);
            $contactInfo->append($contactAuthorization);

            $contactPassword = new Password($this, ['password' => $this->password]);
            $contactAuthorization->append($contactPassword);
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return InfoResponse::class;
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
     * Setting the password of the contact.
     *
     * @param string|null $password authorization information associated with the contact object
     *
     * @return self
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the contact.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
