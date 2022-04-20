<?php

namespace Struzik\EPPClient\Request\Contact\Helper;

/**
 * Parameters aggregation for postal information structure.
 */
class PostalInfo
{
    private ?string $name = null;
    private ?string $organization = null;
    private array $streets = [];
    private ?string $city = null;
    private ?string $state = null;
    private ?string $postalCode = null;
    private ?string $countryCode = null;

    /**
     * Setting the name of the individual or role represented by the contact.
     * REQUIRED for creating, OPTIONAL for updating.
     *
     * @param string|null $name contact name
     */
    public function setName(?string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getting the name of the individual or role represented by the contact.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setting the name of the organization. OPTIONAL.
     *
     * @param string|null $organization the name of the organization
     */
    public function setOrganization(?string $organization = null): self
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Getting the name of the organization.
     */
    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    /**
     * Setting the contact's street address. OPTIONAL.
     *
     * @param array $streets street address
     */
    public function setStreets(array $streets = []): self
    {
        $this->streets = $streets;

        return $this;
    }

    /**
     * Setting the contact's street address.
     */
    public function getStreets(): array
    {
        return $this->streets;
    }

    /**
     * Setting the contact's city. REQUIRED for creating, OPTIONAL for updating.
     *
     * @param string|null $city city
     */
    public function setCity(?string $city = null): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Getting the contact's city.
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Setting the contact's state or province. OPTIONAL.
     *
     * @param string|null $state state or province
     */
    public function setState(?string $state = null): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Getting the contact's state or province.
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Setting the contact's postal code. OPTIONAL.
     *
     * @param string|null $postalCode postal code
     */
    public function setPostalCode(?string $postalCode = null): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Getting the contact's postal code.
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Setting the contact's country code. REQUIRED for creating, OPTIONAL for updating.
     *
     * @param string|null $countryCode country code
     */
    public function setCountryCode(?string $countryCode = null): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Getting the contact's country code.
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }
}
