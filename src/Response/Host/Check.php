<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Response\CommonResponse;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use XPath;

/**
 * Object representation of the response of host checking command.
 */
class Check extends CommonResponse
{
    /**
     * Host is available for creating.
     *
     * @param $host fully qualified name of the host object
     *
     * @return bool
     */
    public function isAvailable($host)
    {
        $hostcdNode = $this->getHostcd($host);
        $node = $this->getFirst('host:name', $hostcdNode);

        return in_array($node->getAttribute('avail'), ['1', 'true']);
    }

    /**
     * The reason why a host is not available for creation.
     *
     * @param $host fully qualified name of the host object
     *
     * @return string
     */
    public function getReason($host)
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
     * @return \DOMElement
     *
     * @throws UnexpectedValueException
     */
    protected function getHostcd($host)
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
