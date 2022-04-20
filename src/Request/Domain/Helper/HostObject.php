<?php

namespace Struzik\EPPClient\Request\Domain\Helper;

/**
 * Parameters aggregation for host object structure.
 */
class HostObject implements HostInterface
{
    private string $host = '';

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
}
