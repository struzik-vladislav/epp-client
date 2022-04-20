<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\CommonResponse;
use XPath;

/**
 * Object representation of the response of host checking command.
 */
class CheckHostResponse extends CommonResponse
{
    /**
     * Host is available for creating.
     *
     * @param string $host fully qualified name of the host object
     */
    public function isAvailable(string $host): bool
    {
        $hostcdNode = $this->getHostcd($host);
        $node = $this->getFirst('host:name', $hostcdNode);

        return in_array($node->getAttribute('avail'), ['1', 'true'], true);
    }

    /**
     * The reason why a host is not available for creation.
     *
     * @param string $host fully qualified name of the host object
     */
    public function getReason(string $host): ?string
    {
        $hostcdNode = $this->getHostcd($host);
        $node = $this->getFirst('host:reason', $hostcdNode);

        return $node === null ? null : $node->nodeValue;
    }

    /**
     * Getting the <host:cd> node by host.
     *
     * @param string $host fully qualified name of the host object
     *
     * @throws UnexpectedValueException
     */
    protected function getHostcd(string $host): \DOMNode
    {
        $pattern = '//epp:epp/epp:response/epp:resData/host:chkData/host:cd[host:name[php:functionString("XPath\quote", text()) = \'%s\']]';
        $query = sprintf($pattern, XPath\quote($host));
        $node = $this->getFirst($query);

        if ($node === null) {
            throw new UnexpectedValueException(sprintf('Host with name [%s] not found.', $host));
        }

        return $node;
    }
}
