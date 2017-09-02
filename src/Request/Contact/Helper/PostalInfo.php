<?php

namespace Struzik\EPPClient\Request\Contact\Helper;

/**
 * Parametres aggregation for postal information structure.
 */
class PostalInfo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $organization;

    /**
     * @var array
     */
    private $streets = [];

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * Setting the name of the individual or role represented by the contact.
     * REQUIRED for creating, OPTIONAL for updating.
     *
     * @param string|null $name contact name
     *
     * @return self
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getting the name of the individual or role represented by the contact.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setting the name of the organization. OPTIONAL.
     *
     * @param string|null $organization the name of the organization
     *
     * @return self
     */
    public function setOrganization($organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Getting the name of the organization.
     *
     * @return string|null
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Setting the contact's street address. OPTIONAL.
     *
     * @param array $streets street address
     *
     * @return self
     */
    public function setStreets(array $streets = [])
    {
        $this->streets = $streets;

        return $this;
    }

    /**
     * Setting the contact's street address.
     *
     * @return array
     */
    public function getStreets()
    {
        return $this->streets;
    }

    /**
     * Setting the contact's city. REQUIRED for creating, OPTIONAL for updating.
     *
     * @param string|null $city city
     *
     * @return self
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Getting the contact's city.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Setting the contact's state or province. OPTIONAL.
     *
     * @param string|null $state state or province
     *
     * @return self
     */
    public function setState($state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Getting the contact's state or province.
     *
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Setting the contact's postal code. OPTIONAL.
     *
     * @param string|null $postalCode postal code
     *
     * @return self
     */
    public function setPostalCode($postalCode = null)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Getting the contact's postal code.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Setting the contact's country code. REQUIRED for creating, OPTIONAL for updating.
     *
     * @param string|null $countryCode country code
     *
     * @return self
     */
    public function setCountryCode($countryCode = null)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Getting the contact's country code.
     *
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}
