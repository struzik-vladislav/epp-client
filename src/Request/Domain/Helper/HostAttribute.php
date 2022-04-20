<?php

namespace Struzik\EPPClient\Request\Domain\Helper;

/**
 * Parameters aggregation for host attribute structure.
 */
class HostAttribute implements HostInterface
{
    private string $host = '';
    private array $addresses = [];

    /**
     * Setting the name of the host. REQUIRED.
     *
     * @param string $host fully qualified name of the host object
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Getting the name of the host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Setting the IP addresses to be associated with the host. OPTIONAL.
     *
     * @param array $addresses array of the IP addresses
     */
    public function setAddresses(array $addresses = []): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Getting the IP addresses to be associated with the host.
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }
}
