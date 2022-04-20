<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\RenewNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Domain\DomainCurExpDateNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Node\Domain\DomainRenewNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\RenewDomainResponse;

/**
 * Object representation of the request of domain renewal command.
 */
class RenewDomainRequest extends AbstractRequest
{
    private string $domain = '';
    private ?\DateTimeInterface $expiryDate = null;
    private ?int $period = null;
    private ?string $unit = null;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        if ($this->expiryDate === null) {
            throw new InvalidArgumentException('Invalid parameter "expiryDate".');
        }

        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $renewNode = RenewNode::create($this, $commandNode);
        $domainRenewNode = DomainRenewNode::create($this, $renewNode);
        DomainNameNode::create($this, $domainRenewNode, $this->domain);
        DomainCurExpDateNode::create($this, $domainRenewNode, $this->expiryDate);
        if ($this->period !== null && $this->unit !== null) {
            DomainPeriodNode::create($this, $domainRenewNode, $this->period, $this->unit);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return RenewDomainResponse::class;
    }

    /**
     * Setting the name of the domain. REQUIRED.
     *
     * @param string $domain fully qualified name of the domain object
     */
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Getting the name of the domain.
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Setting the date on which the current validity period ends. REQUIRED.
     *
     * @param \DateTimeInterface $expiryDate current expiry date
     */
    public function setExpiryDate(\DateTimeInterface $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Getting the date on which the current validity period ends.
     */
    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiryDate;
    }

    /**
     * Setting the number of units to be added to the registration period
     * of the domain object. OPTIONAL.
     *
     * @param int|null $period registration period
     */
    public function setPeriod(?int $period = null): self
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Getting the number of units to be added to the registration period
     * of the domain object.
     */
    public function getPeriod(): ?int
    {
        return $this->period;
    }

    /**
     * Setting the unit of the period. OPTIONAL.
     *
     * @param string|null $unit constant of the unit
     */
    public function setUnit(?string $unit = null): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Getting the unit of the period.
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }
}
