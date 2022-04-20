<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\CreateNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Domain\DomainAuthInfoNode;
use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainCreateNode;
use Struzik\EPPClient\Node\Domain\DomainHostAddrNode;
use Struzik\EPPClient\Node\Domain\DomainHostAttrNode;
use Struzik\EPPClient\Node\Domain\DomainHostNameNode;
use Struzik\EPPClient\Node\Domain\DomainHostObjNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Node\Domain\DomainNameserverNode;
use Struzik\EPPClient\Node\Domain\DomainPasswordNode;
use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Node\Domain\DomainRegistrantNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Request\Domain\Helper\HostAttribute as HostAttributeHelper;
use Struzik\EPPClient\Request\Domain\Helper\HostInterface;
use Struzik\EPPClient\Request\Domain\Helper\HostObject as HostObjectHelper;
use Struzik\EPPClient\Response\Domain\CreateDomainResponse;

/**
 * Object representation of the request of domain creating command.
 */
class CreateDomainRequest extends AbstractRequest
{
    private string $domain = '';
    private ?int $period = null;
    private ?string $unit = null;
    private array $nameservers = [];
    private string $registrant = '';
    private array $contacts = [];
    private string $password = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $createNode = CreateNode::create($this, $commandNode);
        $domainCreateNode = DomainCreateNode::create($this, $createNode);
        DomainNameNode::create($this, $domainCreateNode, $this->domain);
        if ($this->period !== null && $this->unit !== null) {
            DomainPeriodNode::create($this, $domainCreateNode, $this->period, $this->unit);
        }
        if (count($this->nameservers) > 0) {
            $domainNameserverNode = DomainNameserverNode::create($this, $domainCreateNode);
            foreach ($this->nameservers as $nameserver) {
                if ($nameserver instanceof HostObjectHelper) {
                    DomainHostObjNode::create($this, $domainNameserverNode, $nameserver->getHost());
                } elseif ($nameserver instanceof HostAttributeHelper) {
                    $domainHostAttrNode = DomainHostAttrNode::create($this, $domainNameserverNode);
                    DomainHostNameNode::create($this, $domainHostAttrNode, $nameserver->getHost());
                    foreach ($nameserver->getAddresses() as $address) {
                        DomainHostAddrNode::create($this, $domainHostAttrNode, $address);
                    }
                }
            }
        }
        if ($this->registrant !== '') {
            DomainRegistrantNode::create($this, $domainCreateNode, $this->registrant);
        }
        foreach ($this->contacts as $type => $contact) {
            DomainContactNode::create($this, $domainCreateNode, $type, $contact);
        }
        $domainAuthNode = DomainAuthInfoNode::create($this, $domainCreateNode);
        DomainPasswordNode::create($this, $domainAuthNode, $this->password);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return CreateDomainResponse::class;
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
     * Setting the initial registration period of the domain object. OPTIONAL.
     *
     * @param int|null $period registration period
     */
    public function setPeriod(?int $period = null): self
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Getting the initial registration period of the domain object.
     */
    public function getPeriod(): ?int
    {
        return $this->period;
    }

    /**
     * Setting the unit of the initial registration period. OPTIONAL.
     *
     * @param string|null $unit constant of the unit
     */
    public function setUnit(?string $unit = null): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Getting the unit of the initial registration period.
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * Setting list of the fully qualified names of the delegated host objects
     * or host attributes (name servers) associated with the domain object. OPTIONAL.
     *
     * @param HostInterface[] $nameservers list of the fully qualified names
     */
    public function setNameservers(array $nameservers = []): self
    {
        foreach ($nameservers as $nameserver) {
            if (!($nameserver instanceof HostInterface)) {
                throw new UnexpectedValueException(sprintf('Invalid class of the nameservers item. Items of the nameservers must be instance of %s or %s.', HostObjectHelper::class, HostAttributeHelper::class));
            }
        }

        $this->nameservers = $nameservers;

        return $this;
    }

    /**
     * Getting list of the fully qualified names of the delegated host objects
     * or host attributes (name servers) associated with the domain object.
     */
    public function getNameservers(): array
    {
        return $this->nameservers;
    }

    /**
     * Setting the identifier for the human or organizational social information (contact)
     * object to be associated with the domain object as the object registrant. OPTIONAL.
     *
     * @param string $registrant identifier of the contact
     */
    public function setRegistrant(string $registrant): self
    {
        $this->registrant = $registrant;

        return $this;
    }

    /**
     * Getting the identifier for the human or organizational social information (contact)
     * object to be associated with the domain object as the object registrant.
     */
    public function getRegistrant(): string
    {
        return $this->registrant;
    }

    /**
     * Setting the identifiers for other contact objects to be associated
     * with the domain object. OPTIONAL.
     *
     * @param array $contacts identifiers of the contacts with types
     */
    public function setContacts(array $contacts = []): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Getting the identifiers for other contact objects to be associated
     * with the domain object.
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * Setting the password of the domain. REQUIRED.
     *
     * @param string $password authorization information associated with the domain object
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the domain.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
