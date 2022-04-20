<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\CommonResponse;
use XPath;

/**
 * Object representation of the response of domain checking command.
 */
class CheckDomainResponse extends CommonResponse
{
    /**
     * Domain is available for creating.
     *
     * @param string $domain fully qualified name of the domain object
     */
    public function isAvailable(string $domain): bool
    {
        $domaincdNode = $this->getDomaincd($domain);
        $node = $this->getFirst('domain:name', $domaincdNode);

        return in_array($node->getAttribute('avail'), ['1', 'true'], true);
    }

    /**
     * The reason why a domain is not available for creation.
     *
     * @param string $domain fully qualified name of the domain object
     */
    public function getReason(string $domain): ?string
    {
        $domaincdNode = $this->getDomaincd($domain);
        $node = $this->getFirst('domain:reason', $domaincdNode);

        return $node === null ? null : $node->nodeValue;
    }

    /**
     * Getting the <domain:cd> node by domain name.
     *
     * @param string $domain fully qualified name of the domain object
     *
     * @throws UnexpectedValueException
     */
    protected function getDomaincd(string $domain): \DOMNode
    {
        $pattern = '//epp:epp/epp:response/epp:resData/domain:chkData/domain:cd[domain:name[php:functionString("XPath\quote", text()) = \'%s\']]';
        $query = sprintf($pattern, XPath\quote($domain));
        $node = $this->getFirst($query);

        if ($node === null) {
            throw new UnexpectedValueException(sprintf('Domain with name [%s] not found.', $domain));
        }

        return $node;
    }
}
