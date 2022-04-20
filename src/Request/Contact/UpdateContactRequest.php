<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Common\UpdateNode;
use Struzik\EPPClient\Node\Contact\ContactAddNode;
use Struzik\EPPClient\Node\Contact\ContactAddressNode;
use Struzik\EPPClient\Node\Contact\ContactAuthInfoNode;
use Struzik\EPPClient\Node\Contact\ContactChangeNode;
use Struzik\EPPClient\Node\Contact\ContactCityNode;
use Struzik\EPPClient\Node\Contact\ContactCountryCodeNode;
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
use Struzik\EPPClient\Node\Contact\ContactRemoveNode;
use Struzik\EPPClient\Node\Contact\ContactStateNode;
use Struzik\EPPClient\Node\Contact\ContactStatusNode;
use Struzik\EPPClient\Node\Contact\ContactStreetNode;
use Struzik\EPPClient\Node\Contact\ContactUpdateNode;
use Struzik\EPPClient\Node\Contact\ContactVoiceNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Request\Contact\Helper\Disclose;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo;
use Struzik\EPPClient\Response\Contact\UpdateContactResponse;

/**
 * Object representation of the request of contact updating command.
 */
class UpdateContactRequest extends AbstractRequest
{
    private string $identifier = '';
    private array $statusesForAdding = [];
    private array $statusesForRemoving = [];
    /** @var PostalInfo[] */
    private array $postalInformation = [];
    private ?string $voice = null;
    private ?string $fax = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?Disclose $disclose = null;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $updateNode = UpdateNode::create($this, $commandNode);
        $contactUpdateNode = ContactUpdateNode::create($this, $updateNode);
        ContactIdentifierNode::create($this, $contactUpdateNode, $this->identifier);

        if (count($this->statusesForAdding) > 0) {
            $contactAddNode = ContactAddNode::create($this, $contactUpdateNode);
            foreach ($this->statusesForAdding as $status) {
                ContactStatusNode::create($this, $contactAddNode, $status);
            }
        }
        if (count($this->statusesForRemoving) > 0) {
            $contactRemoveNode = ContactRemoveNode::create($this, $contactUpdateNode);
            foreach ($this->statusesForRemoving as $status) {
                ContactStatusNode::create($this, $contactRemoveNode, $status);
            }
        }

        if (count($this->postalInformation) > 0
            || $this->voice !== null
            || $this->fax !== null
            || $this->email !== null
            || $this->password !== null
            || $this->disclose instanceof Disclose
        ) {
            $contactChangeNode = ContactChangeNode::create($this, $contactUpdateNode);
            foreach ($this->postalInformation as $postalInfoType => $postalInfoObject) {
                $postalInfoNode = ContactPostalInfoNode::create($this, $contactChangeNode, $postalInfoType);
                if ($postalInfoObject->getName() !== null) {
                    ContactNameNode::create($this, $postalInfoNode, $postalInfoObject->getName());
                }
                if ($postalInfoObject->getOrganization() !== null) {
                    ContactOrgNode::create($this, $postalInfoNode, $postalInfoObject->getOrganization());
                }

                if ($postalInfoObject->getStreets() !== []
                    || $postalInfoObject->getCity() !== null
                    || $postalInfoObject->getState() !== null
                    || $postalInfoObject->getPostalCode() !== null
                    || $postalInfoObject->getCountryCode() !== null
                ) {
                    $contactAddressNode = ContactAddressNode::create($this, $postalInfoNode);
                    foreach ($postalInfoObject->getStreets() as $street) {
                        ContactStreetNode::create($this, $contactAddressNode, $street);
                    }
                    ContactCityNode::create($this, $contactAddressNode, $postalInfoObject->getCity());
                    if ($postalInfoObject->getState() !== null) {
                        ContactStateNode::create($this, $contactAddressNode, $postalInfoObject->getState());
                    }
                    if ($postalInfoObject->getPostalCode() !== null) {
                        ContactPostalCodeNode::create($this, $contactAddressNode, $postalInfoObject->getPostalCode());
                    }
                    ContactCountryCodeNode::create($this, $contactAddressNode, $postalInfoObject->getCountryCode());
                }
            }
            if ($this->voice !== null) {
                ContactVoiceNode::create($this, $contactChangeNode, $this->voice);
            }
            if ($this->fax !== null) {
                ContactFaxNode::create($this, $contactChangeNode, $this->fax);
            }
            if ($this->email !== null) {
                ContactEmailNode::create($this, $contactChangeNode, $this->email);
            }
            if ($this->password !== null) {
                $contactAuthInfoNode = ContactAuthInfoNode::create($this, $contactChangeNode);
                ContactPasswordNode::create($this, $contactAuthInfoNode, $this->password);
            }
            if ($this->disclose instanceof Disclose) {
                $contactDiscloseNode = ContactDiscloseNode::create($this, $contactUpdateNode, $this->disclose->getFlag());
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
        }

        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return UpdateContactResponse::class;
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
     * Setting the statuses for adding. OPTIONAL.
     *
     * @param array $statuses array of contact's statuses
     */
    public function setStatusesForAdding(array $statuses = []): self
    {
        $this->statusesForAdding = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for adding.
     */
    public function getStatusesForAdding(): array
    {
        return $this->statusesForAdding;
    }

    /**
     * Setting the statuses for removing. OPTIONAL.
     *
     * @param array $statuses array of contact's statuses
     */
    public function setStatusesForRemoving(array $statuses = []): self
    {
        $this->statusesForRemoving = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for removing.
     */
    public function getStatusesForRemoving(): array
    {
        return $this->statusesForRemoving;
    }

    /**
     * Setting the postal-address information with types. OPTIONAL.
     *
     * @param PostalInfo[] $postalInformation the postal-address information with types
     */
    public function setPostalInformation(array $postalInformation = []): self
    {
        $this->postalInformation = $postalInformation;

        return $this;
    }

    /**
     * Getting the postal-address information with types.
     *
     * @return PostalInfo[]
     */
    public function getPostalInformation(): array
    {
        return $this->postalInformation;
    }

    /**
     * Setting the new contact's voice telephone number. OPTIONAL.
     *
     * @param string|null $voice voice telephone number
     */
    public function setVoice(?string $voice = null): self
    {
        $this->voice = $voice;

        return $this;
    }

    /**
     * Getting the new contact's voice telephone number.
     */
    public function getVoice(): ?string
    {
        return $this->voice;
    }

    /**
     * Setting the new contact's facsimile telephone number. OPTIONAL.
     *
     * @param string|null $fax facsimile telephone number
     */
    public function setFax(?string $fax = null): self
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Getting the new contact's facsimile telephone number.
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * Setting the new contact's email address. OPTIONAL.
     *
     * @param string|null $email email address
     */
    public function setEmail(?string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getting the new contact's email address.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setting the new password of the contact. OPTIONAL.
     *
     * @param string|null $password authorization information associated with the contact object
     */
    public function setPassword(?string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the new password of the contact.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setting the new contact's disclosure preferences. OPTIONAL.
     *
     * @param Disclose|null $disclose disclosure preferences
     */
    public function setDisclose(Disclose $disclose = null): self
    {
        $this->disclose = $disclose;

        return $this;
    }

    /**
     * Getting the new contact's disclosure preferences.
     */
    public function getDisclose(): ?Disclose
    {
        return $this->disclose;
    }
}
