<?php

namespace Struzik\EPPClient\Request\Domain\Helper;

/**
 * Parametres aggregation for host attribute structure.
 */
class HostAttribute implements HostInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $addresses = [];

    /**
     * Setting the name of the host. REQUIRED.
     *
     * @param string $host fully qualified name of the host object
     *
     * @return self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Getting the name of the host.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Setting the IP addresses to be associated with the host. OPTIONAL.
     *
     * @param array $address array of the IP addresses
     *
     * @return self
     */
    public function setAddresses(array $addresses = [])
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Getting the IP addresses to be associated with the host.
     *
     * @return array
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
