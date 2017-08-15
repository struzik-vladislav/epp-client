<?php

namespace Struzik\EPPClient\Request\Contact;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Contact\Create as CreateResponse;
use Struzik\EPPClient\Node\Common\Create as CreateNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Contact\Email;
use Struzik\EPPClient\Node\Contact\Fax;
use Struzik\EPPClient\Node\Contact\Name;
use Struzik\EPPClient\Node\Contact\City;
use Struzik\EPPClient\Node\Contact\Voice;
use Struzik\EPPClient\Node\Contact\State;
use Struzik\EPPClient\Node\Contact\Create as ContactCreateNode;
use Struzik\EPPClient\Node\Contact\Street;
use Struzik\EPPClient\Node\Contact\Identifier;
use Struzik\EPPClient\Node\Contact\Disclose as DiscloseNode;
use Struzik\EPPClient\Node\Contact\Address;
use Struzik\EPPClient\Node\Contact\Password;
use Struzik\EPPClient\Node\Contact\PostalInfo;
use Struzik\EPPClient\Node\Contact\PostalCode;
use Struzik\EPPClient\Node\Contact\DiscloseFax;
use Struzik\EPPClient\Node\Contact\CountryCode;
use Struzik\EPPClient\Node\Contact\DiscloseName;
use Struzik\EPPClient\Node\Contact\Organization;
use Struzik\EPPClient\Node\Contact\DiscloseVoice;
use Struzik\EPPClient\Node\Contact\DiscloseEmail;
use Struzik\EPPClient\Node\Contact\DiscloseAddress;
use Struzik\EPPClient\Node\Contact\AuthorizationInfo;
use Struzik\EPPClient\Node\Contact\DiscloseOrganization;
use Struzik\EPPClient\Request\Contact\Helper\Disclose as DiscloseHelper;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo as PostalInfoHelper;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the request of contact creating command.
 */
class Create extends AbstractRequest
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var array
     */
    private $postalInfo = [
        PostalInfo::TYPE_INT => null,
        PostalInfo::TYPE_LOC => null,
    ];

    /**
     * @var string
     */
    private $voice;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var DiscloseHelper
     */
    private $disclose;

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

        $contactCreate = new ContactCreateNode($this);
        $create->append($contactCreate);

        $contactId = new Identifier($this, ['identifier' => $this->identifier]);
        $contactCreate->append($contactId);

        if (count(array_filter($this->postalInfo)) === 0) {
            throw new UnexpectedValueException('The contact object must contain one or two object with postal-address information');
        }

        foreach ($this->postalInfo as $postalInfoType => $postalInfoObject) {
            if (!($postalInfoObject instanceof PostalInfoHelper)) {
                continue;
            }

            $postalInfo = new PostalInfo($this, ['type' => $postalInfoType]);
            $contactCreate->append($postalInfo);

            if (empty($postalInfoObject->getName())) {
                throw new UnexpectedValueException(sprintf('The field \'name\' cannot be empty. In the PostalInfo object with type \'%s\'.', $postalInfoType));
            }

            $postalInfoName = new Name($this, ['name' => $postalInfoObject->getName()]);
            $postalInfo->append($postalInfoName);

            if (!empty($postalInfoObject->getOrganization())) {
                $postalInfoOrg = new Organization($this, ['organization' => $postalInfoObject->getOrganization()]);
                $postalInfo->append($postalInfoOrg);
            }

            $postalInfoAddr = new Address($this);
            $postalInfo->append($postalInfoAddr);

            foreach ($postalInfoObject->getStreets() as $street) {
                $postalInfoStreet = new Street($this, ['street' => $street]);
                $postalInfoAddr->append($postalInfoStreet);
            }

            if (empty($postalInfoObject->getCity())) {
                throw new UnexpectedValueException(sprintf('The field \'city\' cannot be empty. In the PostalInfo object with type \'%s\'.', $postalInfoType));
            }

            $postalInfoCity = new City($this, ['city' => $postalInfoObject->getCity()]);
            $postalInfoAddr->append($postalInfoCity);

            if (!empty($postalInfoObject->getState())) {
                $postalInfoState = new State($this, ['state' => $postalInfoObject->getState()]);
                $postalInfoAddr->append($postalInfoState);
            }

            if (!empty($postalInfoObject->getPostalCode())) {
                $postalInfoPCode = new PostalCode($this, ['postal-code' => $postalInfoObject->getPostalCode()]);
                $postalInfoAddr->append($postalInfoPCode);
            }

            if (empty($postalInfoObject->getCountryCode())) {
                throw new UnexpectedValueException(sprintf('The field \'countryCode\' cannot be empty. In the PostalInfo object with type \'%s\'.', $postalInfoType));
            }

            $postalInfoCCode = new CountryCode($this, ['country-code' => $postalInfoObject->getCountryCode()]);
            $postalInfoAddr->append($postalInfoCCode);
        }

        if ($this->voice !== null) {
            $contactVoice = new Voice($this, ['voice' => $this->voice]);
            $contactCreate->append($contactVoice);
        }

        if ($this->fax !== null) {
            $contactFax = new Fax($this, ['fax' => $this->fax]);
            $contactCreate->append($contactFax);
        }

        $contactEmail = new Email($this, ['email' => $this->email]);
        $contactCreate->append($contactEmail);

        if (empty($this->password)) {
            throw new UnexpectedValueException('The field \'password\' cannot be empty.');
        }

        $contactAuthinfo = new AuthorizationInfo($this);
        $contactCreate->append($contactAuthinfo);

        $contactPassword = new Password($this, ['password' => $this->password]);
        $contactAuthinfo->append($contactPassword);

        if ($this->disclose instanceof DiscloseHelper) {
            $disclose = new DiscloseNode($this, ['flag' => $this->disclose->getFlag()]);
            $contactCreate->append($disclose);

            if ($this->disclose->getNameInt()) {
                $discloseNameInt = new DiscloseName($this, ['type' => DiscloseName::TYPE_INT]);
                $disclose->append($discloseNameInt);
            }
            if ($this->disclose->getNameLoc()) {
                $discloseNameLoc = new DiscloseName($this, ['type' => DiscloseName::TYPE_LOC]);
                $disclose->append($discloseNameLoc);
            }

            if ($this->disclose->getOrganizationInt()) {
                $discloseOrgInt = new DiscloseOrganization($this, ['type' => DiscloseOrganization::TYPE_INT]);
                $disclose->append($discloseOrgInt);
            }
            if ($this->disclose->getOrganizationLoc()) {
                $discloseOrgLoc = new DiscloseOrganization($this, ['type' => DiscloseOrganization::TYPE_LOC]);
                $disclose->append($discloseOrgLoc);
            }

            if ($this->disclose->getAddressInt()) {
                $discloseAddrInt = new DiscloseAddress($this, ['type' => DiscloseAddress::TYPE_INT]);
                $disclose->append($discloseAddrInt);
            }
            if ($this->disclose->getAddressLoc()) {
                $discloseAddrLoc = new DiscloseAddress($this, ['type' => DiscloseAddress::TYPE_LOC]);
                $disclose->append($discloseAddrLoc);
            }

            if ($this->disclose->getVoice()) {
                $discloseVoice = new DiscloseVoice($this);
                $disclose->append($discloseVoice);
            }

            if ($this->disclose->getFax()) {
                $discloseFax = new DiscloseFax($this);
                $disclose->append($discloseFax);
            }

            if ($this->disclose->getEmail()) {
                $discloseEmail = new DiscloseEmail($this);
                $disclose->append($discloseEmail);
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
        return CreateResponse::class;
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
     * Setting the postal-address information by type.
     *
     * @param string           $type       type of the postal-address information
     * @param PostalInfoHelper $postalInfo the postal-address information
     *
     * @return self
     */
    public function setPostalInfoByType($type, PostalInfoHelper $postalInfo = null)
    {
        if (!in_array($type, [PostalInfo::TYPE_INT, PostalInfo::TYPE_LOC])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter \'type\' must be set to \'%s\' or \'%s\'.',
                PostalInfo::TYPE_INT,
                PostalInfo::TYPE_LOC
            ));
        }

        $this->postalInfo[$type] = $postalInfo;

        return $this;
    }

    /**
     * Getting the postal-address information by type.
     *
     * @param string $type type of the postal-address information
     *
     * @return PostalInfoHelper|null
     */
    public function getPostalInfoByType($type)
    {
        if (!in_array($type, [PostalInfo::TYPE_INT, PostalInfo::TYPE_LOC])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter \'type\' must be set to \'%s\' or \'%s\'.',
                PostalInfo::TYPE_INT,
                PostalInfo::TYPE_LOC
            ));
        }

        return isset($this->postalInfo[$type]) ? $this->postalInfo[$type] : null;
    }

    /**
     * Setting the contact's voice telephone number.
     *
     * @param string|null $voice voice telephone number
     *
     * @return self
     */
    public function setVoice($voice = null)
    {
        $this->voice = $voice;

        return $this;
    }

    /**
     * Getting the contact's voice telephone number.
     *
     * @return string
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * Setting the contact's facsimile telephone number.
     *
     * @param string|null $fax facsimile telephone number
     *
     * @return self
     */
    public function setFax($fax = null)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Getting the contact's facsimile telephone number.
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Setting the contact's email address.
     *
     * @param string $email email address
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getting the contact's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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

    /**
     * Setting the contact's disclosure preferences.
     *
     * @param DiscloseHelper $disclose disclosure preferences
     *
     * @return self
     */
    public function setDisclose(DiscloseHelper $disclose = null)
    {
        $this->disclose = $disclose;

        return $this;
    }

    /**
     * Getting the contact's disclosure preferences.
     *
     * @return DiscloseHelper|null
     */
    public function getDisclose()
    {
        return $this->disclose;
    }
}
