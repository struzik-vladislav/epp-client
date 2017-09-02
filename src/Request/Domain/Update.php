<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\Update as UpdateResponse;
use Struzik\EPPClient\Node\Common\Update as UpdateNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Domain\Add;
use Struzik\EPPClient\Node\Domain\Name;
use Struzik\EPPClient\Node\Domain\Update as DomainUpdateNode;
use Struzik\EPPClient\Node\Domain\Status;
use Struzik\EPPClient\Node\Domain\Remove;
use Struzik\EPPClient\Node\Domain\Change;
use Struzik\EPPClient\Node\Domain\Nameserver;
use Struzik\EPPClient\Node\Domain\Contact;
use Struzik\EPPClient\Node\Domain\HostName;
use Struzik\EPPClient\Node\Domain\Password;
use Struzik\EPPClient\Node\Domain\HostObject;
use Struzik\EPPClient\Node\Domain\Registrant;
use Struzik\EPPClient\Node\Domain\DomainNull;
use Struzik\EPPClient\Node\Domain\HostAddress;
use Struzik\EPPClient\Node\Domain\HostAttribute;
use Struzik\EPPClient\Node\Domain\AuthorizationInfo;
use Struzik\EPPClient\Request\Domain\Helper\HostObject as HostObjectHelper;
use Struzik\EPPClient\Request\Domain\Helper\HostAttribute as HostAttributeHelper;
use Struzik\EPPClient\Request\Domain\Helper\HostInterface;
use Struzik\EPPClient\Request\Domain\Helper\Status as StatusHelper;

/**
 * Object representation of the request of domain updating command.
 */
class Update extends AbstractRequest
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var HostInterface[]
     */
    private $nameserversForAdding = [];

    /**
     * @var HostInterface[]
     */
    private $nameserversForRemoving = [];

    /**
     * @var array
     */
    private $contactsForAdding = [];

    /**
     * @var array
     */
    private $contactsForRemoving = [];

    /**
     * @var StatusHelper[]
     */
    private $statusesForAdding = [];

    /**
     * @var StatusHelper[]
     */
    private $statusesForRemoving = [];

    /**
     * @var string
     */
    private $registrant;

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

        $update = new UpdateNode($this);
        $command->append($update);

        $domainUpdate = new DomainUpdateNode($this);
        $update->append($domainUpdate);

        $domainName = new Name($this, ['domain' => $this->domain]);
        $domainUpdate->append($domainName);

        if (count($this->nameserversForAdding) > 0
            || count($this->contactsForAdding) > 0
            || count($this->statusesForAdding) > 0
        ) {
            $domainAdd = new Add($this);
            $domainUpdate->append($domainAdd);

            if (count($this->nameserversForAdding) > 0) {
                $domainNS = new Nameserver($this);
                $domainAdd->append($domainNS);

                foreach ($this->nameserversForAdding as $nameserver) {
                    if ($nameserver instanceof HostObjectHelper) {
                        $hostObject = new HostObject($this, ['host' => $nameserver->getHost()]);
                        $domainNS->append($hostObject);
                    } elseif ($nameserver instanceof HostAttributeHelper) {
                        $hostAttribute = new HostAttribute($this);
                        $domainNS->append($hostAttribute);

                        $hostName = new HostName($this, ['host' => $nameserver->getHost()]);
                        $hostAttribute->append($hostName);

                        foreach ($nameserver->getAddresses() as $address) {
                            $hostAddress = new HostAddress($this, ['address' => $address]);
                            $hostAttribute->append($hostAddress);
                        }
                    }
                }
            }

            foreach ($this->contactsForAdding as $type => $contact) {
                $domainContact = new Contact($this, [
                    'type' => $type,
                    'contact' => $contact,
                ]);
                $domainAdd->append($domainContact);
            }

            foreach ($this->statusesForAdding as $statusHelper) {
                $domainStatus = new Status($this, [
                    'status' => $statusHelper->getStatus(),
                    'language' => $statusHelper->getLanguage(),
                    'reason' => $statusHelper->getReason(),
                ]);
                $domainAdd->append($domainStatus);
            }
        }

        if (count($this->nameserversForRemoving) > 0
            || count($this->contactsForRemoving) > 0
            || count($this->statusesForRemoving) > 0
        ) {
            $domainRemove = new Remove($this);
            $domainUpdate->append($domainRemove);

            if (count($this->nameserversForRemoving) > 0) {
                $domainNS = new Nameserver($this);
                $domainRemove->append($domainNS);

                foreach ($this->nameserversForRemoving as $nameserver) {
                    if ($nameserver instanceof HostObjectHelper) {
                        $hostObject = new HostObject($this, ['host' => $nameserver->getHost()]);
                        $domainNS->append($hostObject);
                    } elseif ($nameserver instanceof HostAttributeHelper) {
                        $hostAttribute = new HostAttribute($this);
                        $domainNS->append($hostAttribute);

                        $hostName = new HostName($this, ['host' => $nameserver->getHost()]);
                        $hostAttribute->append($hostName);

                        foreach ($nameserver->getAddresses() as $address) {
                            $hostAddress = new HostAddress($this, ['address' => $address]);
                            $hostAttribute->append($hostAddress);
                        }
                    }
                }
            }

            foreach ($this->contactsForRemoving as $type => $contact) {
                $domainContact = new Contact($this, [
                    'type' => $type,
                    'contact' => $contact,
                ]);
                $domainRemove->append($domainContact);
            }

            foreach ($this->statusesForRemoving as $statusHelper) {
                $domainStatus = new Status($this, [
                    'status' => $statusHelper->getStatus(),
                    'language' => $statusHelper->getLanguage(),
                    'reason' => $statusHelper->getReason(),
                ]);
                $domainRemove->append($domainStatus);
            }
        }

        if ($this->registrant !== null || $this->password !== null) {
            $domainChange = new Change($this);
            $domainUpdate->append($domainChange);

            if ($this->registrant !== null) {
                $domainRegistrant = new Registrant($this, ['registrant' => $this->registrant]);
                $domainChange->append($domainRegistrant);
            }

            if ($this->password !== null) {
                $domainAuth = new AuthorizationInfo($this);
                $domainChange->append($domainAuth);

                if ($this->password === '') {
                    $domainNull = new DomainNull($this);
                    $domainAuth->append($domainNull);
                } else {
                    $password = new Password($this, ['password' => $this->password]);
                    $domainAuth->append($password);
                }
            }
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return UpdateResponse::class;
    }

    /**
     * Setting the name of the domain. REQUIRED.
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
     * Getting the name of the domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Setting the nameservers for adding. OPTIONAL.
     *
     * @param HostInterface[] $nameservers array of domain's nameservers
     *
     * @return self
     */
    public function setNameserversForAdding(array $nameservers = [])
    {
        $this->nameserversForAdding = $nameservers;

        return $this;
    }

    /**
     * Getting the nameservers for adding.
     *
     * @return HostInterface[]
     */
    public function getNameserversForAdding()
    {
        return $this->nameserversForAdding;
    }

    /**
     * Setting the nameservers for removing. OPTIONAL.
     *
     * @param HostInterface[] $nameservers array of domain's nameservers
     *
     * @return self
     */
    public function setNameserversForRemoving(array $nameservers = [])
    {
        $this->nameserversForRemoving = $nameservers;

        return $this;
    }

    /**
     * Getting the nameservers for removing.
     *
     * @return HostInterface[]
     */
    public function getNameserversForRemoving()
    {
        return $this->nameserversForRemoving;
    }

    /**
     * Setting the contacts for adding. OPTIONAL.
     *
     * @param array $contacts array of domain's contacts
     *
     * @return self
     */
    public function setContactsForAdding(array $contacts = [])
    {
        $this->contactsForAdding = $contacts;

        return $this;
    }

    /**
     * Getting the contacts for adding.
     *
     * @return array
     */
    public function getContactsForAdding()
    {
        return $this->contactsForAdding;
    }

    /**
     * Setting the contacts for removing. OPTIONAL.
     *
     * @param array $contacts array of domain's contacts
     *
     * @return self
     */
    public function setContactsForRemoving(array $contacts = [])
    {
        $this->contactsForRemoving = $contacts;

        return $this;
    }

    /**
     * Getting the contacts for removing.
     *
     * @return array
     */
    public function getContactsForRemoving()
    {
        return $this->contactsForRemoving;
    }

    /**
     * Setting the statuses for adding. OPTIONAL.
     *
     * @param StatusHelper[] $statuses array of domain's statuses
     *
     * @return self
     */
    public function setStatusesForAdding(array $statuses = [])
    {
        $this->statusesForAdding = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for adding.
     *
     * @return StatusHelper[]
     */
    public function getStatusesForAdding()
    {
        return $this->statusesForAdding;
    }

    /**
     * Setting the statuses for removing. OPTIONAL.
     *
     * @param StatusHelper[] $statuses array of domain's statuses
     *
     * @return self
     */
    public function setStatusesForRemoving(array $statuses = [])
    {
        $this->statusesForRemoving = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for removing.
     *
     * @return StatusHelper[]
     */
    public function getStatusesForRemoving()
    {
        return $this->statusesForRemoving;
    }

    /**
     * Setting the identifier for the human or organizational social information (contact)
     * object to be associated with the domain object as the object registrant. OPTIONAL.
     *
     * @param string|null $registrant identifier of the contact
     *
     * @return self
     */
    public function setRegistrant($registrant = null)
    {
        $this->registrant = $registrant;

        return $this;
    }

    /**
     * Getting the identifier for the human or organizational social information (contact)
     * object to be associated with the domain object as the object registrant.
     *
     * @return string|null
     */
    public function getRegistrant()
    {
        return $this->registrant;
    }

    /**
     * Setting the password of the domain. OPTIONAL.
     *
     * @param string|null $password authorization information associated with the domain object
     *
     * @return self
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the domain.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }
}
