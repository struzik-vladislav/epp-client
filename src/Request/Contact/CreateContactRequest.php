<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\CreateNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Contact\ContactAddressNode;
use Struzik\EPPClient\Node\Contact\ContactAuthInfoNode;
use Struzik\EPPClient\Node\Contact\ContactCityNode;
use Struzik\EPPClient\Node\Contact\ContactCountryCodeNode;
use Struzik\EPPClient\Node\Contact\ContactCreateNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseAddrNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseEmailNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseFaxNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseNameNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseOrgNode;
use Struzik\EPPClient\Node\Contact\ContactDiscloseVoiceNode;
use Struzik\EPPClient\Node\Contact\ContactEmailNode;
use Struzik\EPPClient\Node\Contact\ContactFaxNode;
use Struzik\EPPClient\Node\Contact\ContactIdentifierNode;
use Struzik\EPPClient\Node\Contact\ContactNameNode;
use Struzik\EPPClient\Node\Contact\ContactOrgNode;
use Struzik\EPPClient\Node\Contact\ContactPasswordNode;
use Struzik\EPPClient\Node\Contact\ContactPostalCodeNode;
use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Node\Contact\ContactStateNode;
use Struzik\EPPClient\Node\Contact\ContactStreetNode;
use Struzik\EPPClient\Node\Contact\ContactVoiceNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Request\Contact\Helper\Disclose;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo as PostalInfoHelper;
use Struzik\EPPClient\Response\Contact\CreateContactResponse;

/**
 * Object representation of the request of contact creating command.
 */
class CreateContactRequest extends AbstractRequest
{
    private string $identifier = '';
    /** @var PostalInfoHelper[] */
    private array $postalInformation = [];
    private ?string $voice = null;
    private ?string $fax = null;
    private string $email = '';
    private string $password = '';
    private ?Disclose $disclose = null;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $createNode = CreateNode::create($this, $commandNode);
        $contactCreateNode = ContactCreateNode::create($this, $createNode);
        ContactIdentifierNode::create($this, $contactCreateNode, $this->identifier);

        if (count($this->postalInformation) === 0) {
            throw new UnexpectedValueException('The contact object must contain one or two object with postal-address information.');
        }
        foreach ($this->postalInformation as $postalInfoType => $postalInfoObject) {
            $postalInfoNode = ContactPostalInfoNode::create($this, $contactCreateNode, $postalInfoType);
            if (empty($postalInfoObject->getName())) {
                throw new UnexpectedValueException(sprintf('The property "name" cannot be empty. In the PostalInfo object with type "%s".', $postalInfoType));
            }
            ContactNameNode::create($this, $postalInfoNode, $postalInfoObject->getName());
            if (!empty($postalInfoObject->getOrganization())) {
                ContactOrgNode::create($this, $postalInfoNode, $postalInfoObject->getOrganization());
            }
            $contactAddressNode = ContactAddressNode::create($this, $postalInfoNode);
            foreach ($postalInfoObject->getStreets() as $street) {
                ContactStreetNode::create($this, $contactAddressNode, $street);
            }
            if (empty($postalInfoObject->getCity())) {
                throw new UnexpectedValueException(sprintf('The property "city" cannot be empty. In the PostalInfo object with type "%s".', $postalInfoType));
            }
            ContactCityNode::create($this, $contactAddressNode, $postalInfoObject->getCity());
            if (!empty($postalInfoObject->getState())) {
                ContactStateNode::create($this, $contactAddressNode, $postalInfoObject->getState());
            }
            if (!empty($postalInfoObject->getPostalCode())) {
                ContactPostalCodeNode::create($this, $contactAddressNode, $postalInfoObject->getPostalCode());
            }
            if (empty($postalInfoObject->getCountryCode())) {
                throw new UnexpectedValueException(sprintf('The field "countryCode" cannot be empty. In the PostalInfo object with type "%s".', $postalInfoType));
            }
            ContactCountryCodeNode::create($this, $contactAddressNode, $postalInfoObject->getCountryCode());
        }
        if (!empty($this->voice)) {
            ContactVoiceNode::create($this, $contactCreateNode, $this->voice);
        }
        if (!empty($this->fax)) {
            ContactFaxNode::create($this, $contactCreateNode, $this->fax);
        }
        ContactEmailNode::create($this, $contactCreateNode, $this->email);
        $contactAuthInfoNode = ContactAuthInfoNode::create($this, $contactCreateNode);
        ContactPasswordNode::create($this, $contactAuthInfoNode, $this->password);
        if ($this->disclose instanceof Disclose) {
            $contactDiscloseNode = ContactDiscloseNode::create($this, $contactCreateNode, $this->disclose->getFlag());
            if ($this->disclose->getNameInt()) {
                ContactDiscloseNameNode::create($this, $contactDiscloseNode, ContactDiscloseNameNode::TYPE_INT);
            }
            if ($this->disclose->getNameLoc()) {
                ContactDiscloseNameNode::create($this, $contactDiscloseNode, ContactDiscloseNameNode::TYPE_LOC);
            }
            if ($this->disclose->getOrganizationInt()) {
                ContactDiscloseOrgNode::create($this, $contactDiscloseNode, ContactDiscloseOrgNode::TYPE_INT);
            }
            if ($this->disclose->getOrganizationLoc()) {
                ContactDiscloseOrgNode::create($this, $contactDiscloseNode, ContactDiscloseOrgNode::TYPE_LOC);
            }
            if ($this->disclose->getAddressInt()) {
                ContactDiscloseAddrNode::create($this, $contactDiscloseNode, ContactDiscloseAddrNode::TYPE_INT);
            }
            if ($this->disclose->getAddressLoc()) {
                ContactDiscloseAddrNode::create($this, $contactDiscloseNode, ContactDiscloseAddrNode::TYPE_LOC);
            }
            if ($this->disclose->getVoice()) {
                ContactDiscloseVoiceNode::create($this, $contactDiscloseNode);
            }
            if ($this->disclose->getFax()) {
                ContactDiscloseFaxNode::create($this, $contactDiscloseNode);
            }
            if ($this->disclose->getEmail()) {
                ContactDiscloseEmailNode::create($this, $contactDiscloseNode);
            }
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return CreateContactResponse::class;
    }

    /**
     * Setting the identifier of the contact. REQUIRED.
     *
     * @param string $identifier contact identifier
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Getting the identifier of the contact.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Setting the postal-address information with types. REQUIRED.
     *
     * @param PostalInfoHelper[] $postalInformation the postal-address information with types
     */
    public function setPostalInformation(array $postalInformation = []): self
    {
        $this->postalInformation = $postalInformation;

        return $this;
    }

    /**
     * Getting the postal-address information with types.
     *
     * @return PostalInfoHelper[]
     */
    public function getPostalInformation(): array
    {
        return $this->postalInformation;
    }

    /**
     * Setting the contact's voice telephone number. OPTIONAL.
     *
     * @param string|null $voice voice telephone number
     */
    public function setVoice(?string $voice = null): self
    {
        $this->voice = $voice;

        return $this;
    }

    /**
     * Getting the contact's voice telephone number.
     */
    public function getVoice(): ?string
    {
        return $this->voice;
    }

    /**
     * Setting the contact's facsimile telephone number. OPTIONAL.
     *
     * @param string|null $fax facsimile telephone number
     */
    public function setFax(?string $fax = null): self
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Getting the contact's facsimile telephone number.
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * Setting the contact's email address. REQUIRED.
     *
     * @param string $email email address
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getting the contact's email address.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Setting the password of the contact. REQUIRED.
     *
     * @param string $password authorization information associated with the contact object
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the contact.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setting the contact's disclosure preferences.
     *
     * @param Disclose|null $disclose disclosure preferences
     */
    public function setDisclose(?Disclose $disclose = null): self
    {
        $this->disclose = $disclose;

        return $this;
    }

    /**
     * Getting the contact's disclosure preferences.
     */
    public function getDisclose(): ?Disclose
    {
        return $this->disclose;
    }
}
