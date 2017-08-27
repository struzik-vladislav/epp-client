<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\Transfer as TransferResponse;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\Transfer as TransferNode;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Domain\Name;
use Struzik\EPPClient\Node\Domain\Period;
use Struzik\EPPClient\Node\Domain\Transfer;
use Struzik\EPPClient\Node\Domain\Password;
use Struzik\EPPClient\Node\Domain\AuthorizationInfo;

/**
 * Object representation of the request of the domain transferring with operation 'request'.
 */
class TransferRequest extends AbstractRequest
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var int
     */
    private $period;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $passwordROIdentifier;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $transfer = new TransferNode($this, ['operation' => TransferNode::OPERATION_REQUEST]);
        $command->append($transfer);

        $domainTransfer = new Transfer($this);
        $transfer->append($domainTransfer);

        $domainName = new Name($this, ['domain' => $this->domain]);
        $domainTransfer->append($domainName);

        if ($this->period !== null && $this->unit !== null) {
            $domainPeriod = new Period($this, ['period' => $this->period, 'unit' => $this->unit]);
            $domainTransfer->append($domainPeriod);
        }

        $domainAuthorization = new AuthorizationInfo($this);
        $domainTransfer->append($domainAuthorization);

        $domainPassword = new Password($this, [
            'password' => $this->password,
            'object-id' => $this->passwordROIdentifier,
        ]);
        $domainAuthorization->append($domainPassword);

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
     * Getting the name of the domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Setting the number of units to be added to the registration period
     * of the domain object at completion of the transfer process.
     *
     * @param int $period number of units
     *
     * @return self
     */
    public function setPeriod($period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Getting the number of units to be added to the registration period
     * of the domain object at completion of the transfer process.
     *
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Setting the unit of the period.
     *
     * @param string $unit constant of the unit
     *
     * @return self
     */
    public function setUnit($unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Getting the unit of the period.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Setting the name of the domain.
     *
     * @param string $domain fully qualified name of the domain object
     *
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Setting the password.
     *
     * @param string|null $password associated authorization information
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
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setting registry object identifier associated with the password.
     *
     * @param string|null $passwordROIdentifier object identifier
     *
     * @return self
     */
    public function setPasswordROIdentifier($passwordROIdentifier = null)
    {
        $this->passwordROIdentifier = $passwordROIdentifier;

        return $this;
    }

    /**
     * Getting registry object identifier associated with the password.
     *
     * @return string
     */
    public function getPasswordROIdentifier()
    {
        return $this->passwordROIdentifier;
    }
}
