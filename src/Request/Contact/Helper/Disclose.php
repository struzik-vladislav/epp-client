<?php

namespace Struzik\EPPClient\Request\Contact\Helper;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Contact\ContactDiscloseNode;

/**
 * Parameters aggregation for information disclosing structure.
 */
class Disclose
{
    private string $flag;
    private bool $nameInt = false;
    private bool $nameLoc = false;
    private bool $organizationInt = false;
    private bool $organizationLoc = false;
    private bool $addressInt = false;
    private bool $addressLoc = false;
    private bool $voice = false;
    private bool $fax = false;
    private bool $email = false;

    public function __construct(string $flag)
    {
        $this->setFlag($flag);
    }

    /**
     * Setting value of the 'flag' attribute of <disclose> node.
     *
     * @param string $flag 'flag' attribute value
     *
     * @throws UnexpectedValueException
     */
    public function setFlag(string $flag): self
    {
        if (!in_array($flag, [ContactDiscloseNode::FLAG_HIDE, ContactDiscloseNode::ALT_FLAG_HIDE, ContactDiscloseNode::FLAG_SHOW, ContactDiscloseNode::ALT_FLAG_SHOW], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter "flag" must be set to "%s", "%s", "%s" or "%s".', ContactDiscloseNode::FLAG_HIDE, ContactDiscloseNode::ALT_FLAG_HIDE, ContactDiscloseNode::FLAG_SHOW, ContactDiscloseNode::ALT_FLAG_SHOW));
        }

        $this->flag = $flag;

        return $this;
    }

    /**
     * Getting value of the 'flag' attribute of <disclose> node.
     */
    public function getFlag(): string
    {
        return $this->flag;
    }

    /**
     * Setting disclosing for the name of the contact with type 'int'.
     *
     * @param bool $nameInt disclose information
     */
    public function setNameInt(bool $nameInt): self
    {
        $this->nameInt = $nameInt;

        return $this;
    }

    /**
     * Getting disclosing for the name of the contact with type 'int'.
     */
    public function getNameInt(): bool
    {
        return $this->nameInt;
    }

    /**
     * Setting disclosing for the name of the contact with type 'loc'.
     *
     * @param bool $nameLoc disclose information
     */
    public function setNameLoc(bool $nameLoc): self
    {
        $this->nameLoc = $nameLoc;

        return $this;
    }

    /**
     * Getting disclosing for the name of the contact with type 'loc'.
     */
    public function getNameLoc(): bool
    {
        return $this->nameLoc;
    }

    /**
     * Setting disclosing for the organization of the contact with type 'int'.
     *
     * @param bool $organizationInt disclose information
     */
    public function setOrganizationInt(bool $organizationInt): self
    {
        $this->organizationInt = $organizationInt;

        return $this;
    }

    /**
     * Getting disclosing for the organization of the contact with type 'int'.
     */
    public function getOrganizationInt(): bool
    {
        return $this->organizationInt;
    }

    /**
     * Setting disclosing for the organization of the contact with type 'loc'.
     *
     * @param bool $organizationLoc disclose information
     */
    public function setOrganizationLoc(bool $organizationLoc): self
    {
        $this->organizationLoc = $organizationLoc;

        return $this;
    }

    /**
     * Getting disclosing for the organization of the contact with type 'loc'.
     */
    public function getOrganizationLoc(): bool
    {
        return $this->organizationLoc;
    }

    /**
     * Setting disclosing for the address of the contact with type 'int'.
     *
     * @param bool $addressInt disclose information
     */
    public function setAddressInt(bool $addressInt): self
    {
        $this->addressInt = $addressInt;

        return $this;
    }

    /**
     * Getting disclosing for the address of the contact with type 'int'.
     */
    public function getAddressInt(): bool
    {
        return $this->addressInt;
    }

    /**
     * Setting disclosing for the address of the contact with type 'loc'.
     *
     * @param bool $addressLoc disclose information
     */
    public function setAddressLoc(bool $addressLoc): self
    {
        $this->addressLoc = $addressLoc;

        return $this;
    }

    /**
     * Getting disclosing for the address of the contact with type 'loc'.
     */
    public function getAddressLoc(): bool
    {
        return $this->addressLoc;
    }

    /**
     * Setting disclosing for the voice of the contact.
     *
     * @param bool $voice disclose information
     */
    public function setVoice(bool $voice): self
    {
        $this->voice = $voice;

        return $this;
    }

    /**
     * Getting disclosing for the voice of the contact.
     */
    public function getVoice(): bool
    {
        return $this->voice;
    }

    /**
     * Setting disclosing for the fax of the contact.
     *
     * @param bool $fax disclose information
     */
    public function setFax(bool $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Getting disclosing for the fax of the contact.
     */
    public function getFax(): bool
    {
        return $this->fax;
    }

    /**
     * Setting disclosing for the email of the contact.
     *
     * @param bool $email disclose information
     */
    public function setEmail(bool $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getting disclosing for the email of the contact.
     */
    public function getEmail(): bool
    {
        return $this->email;
    }
}
