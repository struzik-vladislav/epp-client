<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Common\UpdateNode;
use Struzik\EPPClient\Node\Domain\DomainAddNode;
use Struzik\EPPClient\Node\Domain\DomainAuthInfoNode;
use Struzik\EPPClient\Node\Domain\DomainChangeNode;
use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainHostAddrNode;
use Struzik\EPPClient\Node\Domain\DomainHostAttrNode;
use Struzik\EPPClient\Node\Domain\DomainHostNameNode;
use Struzik\EPPClient\Node\Domain\DomainHostObjNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Node\Domain\DomainNameserverNode;
use Struzik\EPPClient\Node\Domain\DomainNullNode;
use Struzik\EPPClient\Node\Domain\DomainPasswordNode;
use Struzik\EPPClient\Node\Domain\DomainRegistrantNode;
use Struzik\EPPClient\Node\Domain\DomainRemoveNode;
use Struzik\EPPClient\Node\Domain\DomainStatusNode;
use Struzik\EPPClient\Node\Domain\DomainUpdateNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Request\Domain\Helper\HostAttribute as HostAttributeHelper;
use Struzik\EPPClient\Request\Domain\Helper\HostInterface;
use Struzik\EPPClient\Request\Domain\Helper\HostObject as HostObjectHelper;
use Struzik\EPPClient\Request\Domain\Helper\Status as StatusHelper;
use Struzik\EPPClient\Response\Domain\UpdateDomainResponse;

/**
 * Object representation of the request of domain updating command.
 */
class UpdateDomainRequest extends AbstractRequest
{
    private string $domain = '';
    /** @var HostInterface[] */
    private array $nameserversForAdding = [];
    /** @var HostInterface[] */
    private array $nameserversForRemoving = [];
    private array $contactsForAdding = [];
    private array $contactsForRemoving = [];
    /** @var StatusHelper[] */
    private array $statusesForAdding = [];
    /** @var StatusHelper[] */
    private array $statusesForRemoving = [];
    private string $registrant = '';
    private ?string $password = null;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $updateNode = UpdateNode::create($this, $commandNode);
        $domainUpdateNode = DomainUpdateNode::create($this, $updateNode);
        DomainNameNode::create($this, $domainUpdateNode, $this->domain);
        if (count($this->nameserversForAdding) > 0
            || count($this->contactsForAdding) > 0
            || count($this->statusesForAdding) > 0
        ) {
            $domainAddNode = DomainAddNode::create($this, $domainUpdateNode);
            if (count($this->nameserversForAdding) > 0) {
                $domainNameserverNode = DomainNameserverNode::create($this, $domainAddNode);
                foreach ($this->nameserversForAdding as $nameserver) {
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
            foreach ($this->contactsForAdding as $type => $contact) {
                DomainContactNode::create($this, $domainAddNode, $type, $contact);
            }
            foreach ($this->statusesForAdding as $statusHelper) {
                DomainStatusNode::create($this, $domainAddNode, $statusHelper->getStatus(), $statusHelper->getReason(), $statusHelper->getLanguage());
            }
        }
        if (count($this->nameserversForRemoving) > 0
            || count($this->contactsForRemoving) > 0
            || count($this->statusesForRemoving) > 0
        ) {
            $domainRemoveNode = DomainRemoveNode::create($this, $domainUpdateNode);
            if (count($this->nameserversForRemoving) > 0) {
                $domainNameserverNode = DomainNameserverNode::create($this, $domainRemoveNode);
                foreach ($this->nameserversForRemoving as $nameserver) {
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
            foreach ($this->contactsForRemoving as $type => $contact) {
                DomainContactNode::create($this, $domainRemoveNode, $type, $contact);
            }
            foreach ($this->statusesForRemoving as $statusHelper) {
                DomainStatusNode::create($this, $domainRemoveNode, $statusHelper->getStatus(), $statusHelper->getReason(), $statusHelper->getLanguage());
            }
        }
        if ($this->registrant !== '' || $this->password !== null) {
            $domainChangeNode = DomainChangeNode::create($this, $domainUpdateNode);
            if ($this->registrant !== '') {
                DomainRegistrantNode::create($this, $domainChangeNode, $this->registrant);
            }
            if ($this->password !== null) {
                $domainAuthNode = DomainAuthInfoNode::create($this, $domainChangeNode);
                if ($this->password === '') {
                    DomainNullNode::create($this, $domainAuthNode);
                } else {
                    DomainPasswordNode::create($this, $domainAuthNode, $this->password);
                }
            }
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return UpdateDomainResponse::class;
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
     * Setting the nameservers for adding. OPTIONAL.
     *
     * @param HostInterface[] $nameservers array of domain's nameservers
     */
    public function setNameserversForAdding(array $nameservers = []): self
    {
        foreach ($nameservers as $nameserver) {
            if (!($nameserver instanceof HostInterface)) {
                throw new UnexpectedValueException(sprintf('Invalid class of the nameservers item. Items of the nameservers must be instance of %s or %s.', HostObjectHelper::class, HostAttributeHelper::class));
            }
        }

        $this->nameserversForAdding = $nameservers;

        return $this;
    }

    /**
     * Getting the nameservers for adding.
     *
     * @return HostInterface[]
     */
    public function getNameserversForAdding(): array
    {
        return $this->nameserversForAdding;
    }

    /**
     * Setting the nameservers for removing. OPTIONAL.
     *
     * @param HostInterface[] $nameservers array of domain's nameservers
     */
    public function setNameserversForRemoving(array $nameservers = []): self
    {
        foreach ($nameservers as $nameserver) {
            if (!($nameserver instanceof HostInterface)) {
                throw new UnexpectedValueException(sprintf('Invalid class of the nameservers item. Items of the nameservers must be instance of %s or %s.', HostObjectHelper::class, HostAttributeHelper::class));
            }
        }

        $this->nameserversForRemoving = $nameservers;

        return $this;
    }

    /**
     * Getting the nameservers for removing.
     *
     * @return HostInterface[]
     */
    public function getNameserversForRemoving(): array
    {
        return $this->nameserversForRemoving;
    }

    /**
     * Setting the contacts for adding. OPTIONAL.
     *
     * @param array $contacts array of domain's contacts
     */
    public function setContactsForAdding(array $contacts = []): self
    {
        $this->contactsForAdding = $contacts;

        return $this;
    }

    /**
     * Getting the contacts for adding.
     */
    public function getContactsForAdding(): array
    {
        return $this->contactsForAdding;
    }

    /**
     * Setting the contacts for removing. OPTIONAL.
     *
     * @param array $contacts array of domain's contacts
     */
    public function setContactsForRemoving(array $contacts = []): self
    {
        $this->contactsForRemoving = $contacts;

        return $this;
    }

    /**
     * Getting the contacts for removing.
     */
    public function getContactsForRemoving(): array
    {
        return $this->contactsForRemoving;
    }

    /**
     * Setting the statuses for adding. OPTIONAL.
     *
     * @param StatusHelper[] $statuses array of domain's statuses
     */
    public function setStatusesForAdding(array $statuses = []): self
    {
        foreach ($statuses as $status) {
            if (!($status instanceof StatusHelper)) {
                throw new UnexpectedValueException(sprintf('Invalid class of the statuses item. Items of the statuses must be instance of %s.', StatusHelper::class));
            }
        }

        $this->statusesForAdding = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for adding.
     *
     * @return StatusHelper[]
     */
    public function getStatusesForAdding(): array
    {
        return $this->statusesForAdding;
    }

    /**
     * Setting the statuses for removing. OPTIONAL.
     *
     * @param StatusHelper[] $statuses array of domain's statuses
     */
    public function setStatusesForRemoving(array $statuses = []): self
    {
        foreach ($statuses as $status) {
            if (!($status instanceof StatusHelper)) {
                throw new UnexpectedValueException(sprintf('Invalid class of the statuses item. Items of the statuses must be instance of %s.', StatusHelper::class));
            }
        }

        $this->statusesForRemoving = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for removing.
     *
     * @return StatusHelper[]
     */
    public function getStatusesForRemoving(): array
    {
        return $this->statusesForRemoving;
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
     * Setting the password of the domain. OPTIONAL.
     *
     * @param string|null $password authorization information associated with the domain object
     */
    public function setPassword(?string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the domain.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
