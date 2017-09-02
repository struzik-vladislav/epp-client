<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\Create as CreateResponse;
use Struzik\EPPClient\Node\Common\Create as CreateNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Domain\Name;
use Struzik\EPPClient\Node\Domain\Create as DomainCreateNode;
use Struzik\EPPClient\Node\Domain\Period;
use Struzik\EPPClient\Node\Domain\Password;
use Struzik\EPPClient\Node\Domain\Contact;
use Struzik\EPPClient\Node\Domain\HostName;
use Struzik\EPPClient\Node\Domain\Registrant;
use Struzik\EPPClient\Node\Domain\Nameserver;
use Struzik\EPPClient\Node\Domain\HostObject;
use Struzik\EPPClient\Node\Domain\HostAddress;
use Struzik\EPPClient\Node\Domain\HostAttribute;
use Struzik\EPPClient\Node\Domain\AuthorizationInfo;
use Struzik\EPPClient\Request\Domain\Helper\HostObject as HostObjectHelper;
use Struzik\EPPClient\Request\Domain\Helper\HostAttribute as HostAttributeHelper;
use Struzik\EPPClient\Request\Domain\Helper\HostInterface;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the request of domain creating command.
 */
class Create extends AbstractRequest
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
     * @var array
     */
    private $nameservers = [];

    /**
     * @var string
     */
    private $registrant;

    /**
     * @var array
     */
    private $contacts = [];

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

        $create = new CreateNode($this);
        $command->append($create);

        $domainCreate = new DomainCreateNode($this);
        $create->append($domainCreate);

        $domainName = new Name($this, ['domain' => $this->domain]);
        $domainCreate->append($domainName);

        if ($this->period !== null && $this->unit !== null) {
            $domainPeriod = new Period($this, ['period' => $this->period, 'unit' => $this->unit]);
            $domainCreate->append($domainPeriod);
        }

        if (count($this->nameservers) > 0) {
            $domainNS = new Nameserver($this);
            $domainCreate->append($domainNS);

            foreach ($this->nameservers as $nameserver) {
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

        if ($this->registrant !== null) {
            $domainRegistrant = new Registrant($this, ['registrant' => $this->registrant]);
            $domainCreate->append($domainRegistrant);
        }

        foreach ($this->contacts as $type => $contact) {
            $domainContact = new Contact($this, [
                'type' => $type,
                'contact' => $contact,
            ]);
            $domainCreate->append($domainContact);
        }

        $domainAuthorization = new AuthorizationInfo($this);
        $domainCreate->append($domainAuthorization);

        $domainPassword = new Password($this, ['password' => $this->password]);
        $domainAuthorization->append($domainPassword);

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return CreateResponse::class;
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
     * Setting the initial registration period of the domain object. OPTIONAL.
     *
     * @param int|null $period registration period
     *
     * @return self
     */
    public function setPeriod($period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Getting the initial registration period of the domain object.
     *
     * @return int|null
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Setting the unit of the initial registration period. OPTIONAL.
     *
     * @param string|null $unit constant of the unit
     *
     * @return self
     */
    public function setUnit($unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Getting the unit of the initial registration period.
     *
     * @return string|null
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Setting list of the fully qualified names of the delegated host objects
     * or host attributes (name servers) associated with the domain object. OPTIONAL.
     *
     * @param array $nameservers list of the fully qualified names
     *
     * @return self
     */
    public function setNameservers(array $nameservers = [])
    {
        foreach ($nameservers as $nameserver) {
            if (!($nameserver instanceof HostInterface)) {
                throw new UnexpectedValueException(sprintf(
                    'Invalid class of the nameservers item. Items of the nameservers must be instance of %s or %s.',
                    HostObjectHelper::class,
                    HostAttributeHelper::class
                ));
            }
        }

        $this->nameservers = $nameservers;

        return $this;
    }

    /**
     * Getting list of the fully qualified names of the delegated host objects
     * or host attributes (name servers) associated with the domain object.
     *
     * @return array
     */
    public function getNameservers()
    {
        return $this->nameservers;
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
     * Setting the identifiers for other contact objects to be associated
     * with the domain object. OPTIONAL.
     *
     * @param array $contacts identifiers of the contacts with types
     *
     * @return self
     */
    public function setContacts(array $contacts = [])
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Getting the identifiers for other contact objects to be associated
     * with the domain object.
     *
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Setting the password of the domain. REQUIRED.
     *
     * @param string $password authorization information associated with the domain object
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the domain.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
