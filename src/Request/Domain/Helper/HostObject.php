<?php

namespace Struzik\EPPClient\Request\Domain\Helper;

/**
 * Parametres aggregation for host object structure.
 */
class HostObject implements HostInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * Setting the name of the host.
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
}
