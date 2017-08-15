<?php

namespace Struzik\EPPClient\Request\Contact\Helper;

use Struzik\EPPClient\Node\Contact\Disclose as DiscloseNode;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Parametres aggregation for information disclosing structure.
 */
class Disclose
{
    /**
     * @var string
     */
    private $flag;

    /**
     * @var bool
     */
    private $nameInt = false;

    /**
     * @var bool
     */
    private $nameLoc = false;

    /**
     * @var bool
     */
    private $organizationInt = false;

    /**
     * @var bool
     */
    private $organizationLoc = false;

    /**
     * @var bool
     */
    private $addressInt = false;

    /**
     * @var bool
     */
    private $addressLoc = false;

    /**
     * @var bool
     */
    private $voice = false;

    /**
     * @var bool
     */
    private $fax = false;

    /**
     * @var bool
     */
    private $email = false;

    /**
     * Setting value of the 'flag' attribute of <disclose> node.
     *
     * @param string $flag 'flag' attribute value
     *
     * @return self
     */
    public function setFlag($flag)
    {
        if (!in_array($flag, [DiscloseNode::FLAG_HIDE, DiscloseNode::ALT_FLAG_HIDE, DiscloseNode::FLAG_SHOW, DiscloseNode::ALT_FLAG_SHOW])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter \'flag\' must be set to \'%s\', \'%s\', \'%s\' or \'%s\'.',
                DiscloseNode::FLAG_HIDE,
                DiscloseNode::ALT_FLAG_HIDE,
                DiscloseNode::FLAG_SHOW,
                DiscloseNode::ALT_FLAG_SHOW
            ));
        }

        $this->flag = $flag;

        return $this;
    }

    /**
     * Getting value of the 'flag' attribute of <disclose> node.
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Setting an disclosing for the name of the contact with type 'int'.
     *
     * @param bool $nameInt disclose information
     *
     * @return self
     */
    public function setNameInt($nameInt)
    {
        $this->nameInt = (bool) $nameInt;

        return $this;
    }

    /**
     * Getting an disclosing for the name of the contact with type 'int'.
     *
     * @return bool
     */
    public function getNameInt()
    {
        return $this->nameInt;
    }

    /**
     * Setting an disclosing for the name of the contact with type 'loc'.
     *
     * @param bool $nameLoc disclose information
     *
     * @return self
     */
    public function setNameLoc($nameLoc)
    {
        $this->nameLoc = (bool) $nameLoc;

        return $this;
    }

    /**
     * Getting an disclosing for the name of the contact with type 'loc'.
     *
     * @return bool
     */
    public function getNameLoc()
    {
        return $this->nameLoc;
    }

    /**
     * Setting an disclosing for the organization of the contact with type 'int'.
     *
     * @param bool $organizationInt disclose information
     *
     * @return self
     */
    public function setOrganizationInt($organizationInt)
    {
        $this->organizationInt = (bool) $organizationInt;

        return $this;
    }

    /**
     * Getting an disclosing for the organization of the contact with type 'int'.
     *
     * @return bool
     */
    public function getOrganizationInt()
    {
        return $this->organizationInt;
    }

    /**
     * Setting an disclosing for the organization of the contact with type 'loc'.
     *
     * @param bool $organizationLoc disclose information
     *
     * @return self
     */
    public function setOrganizationLoc($organizationLoc)
    {
        $this->organizationLoc = (bool) $organizationLoc;

        return $this;
    }

    /**
     * Getting an disclosing for the organization of the contact with type 'loc'.
     *
     * @return bool
     */
    public function getOrganizationLoc()
    {
        return $this->organizationLoc;
    }

    /**
     * Setting an disclosing for the address of the contact with type 'int'.
     *
     * @param bool $addressInt disclose information
     *
     * @return self
     */
    public function setAddressInt($addressInt)
    {
        $this->addressInt = (bool) $addressInt;

        return $this;
    }

    /**
     * Getting an disclosing for the address of the contact with type 'int'.
     *
     * @return bool
     */
    public function getAddressInt()
    {
        return $this->addressInt;
    }

    /**
     * Setting an disclosing for the address of the contact with type 'loc'.
     *
     * @param bool $addressLoc disclose information
     *
     * @return self
     */
    public function setAddressLoc($addressLoc)
    {
        $this->addressLoc = (bool) $addressLoc;

        return $this;
    }

    /**
     * Getting an disclosing for the address of the contact with type 'loc'.
     *
     * @return bool
     */
    public function getAddressLoc()
    {
        return $this->addressLoc;
    }

    /**
     * Setting an disclosing for the voice of the contact.
     *
     * @param bool $voice disclose information
     *
     * @return self
     */
    public function setVoice($voice)
    {
        $this->voice = (bool) $voice;

        return $this;
    }

    /**
     * Getting an disclosing for the voice of the contact.
     *
     * @return bool
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * Setting an disclosing for the fax of the contact.
     *
     * @param bool $fax disclose information
     *
     * @return self
     */
    public function setFax($fax)
    {
        $this->fax = (bool) $fax;

        return $this;
    }

    /**
     * Getting an disclosing for the fax of the contact.
     *
     * @return bool
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Setting an disclosing for the email of the contact.
     *
     * @param bool $email disclose information
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = (bool) $email;

        return $this;
    }

    /**
     * Getting an disclosing for the email of the contact.
     *
     * @return bool
     */
    public function getEmail()
    {
        return $this->email;
    }
}
