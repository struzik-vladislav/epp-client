<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use XPath;

/**
 * Object representation of the response of domain checking command.
 */
class Check extends CommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS;
    }

    /**
     * Domain is available for creating.
     *
     * @param $domain fully qualified name of the domain object
     *
     * @return bool
     */
    public function isAvailable($domain)
    {
        $domaincdNode = $this->getDomaincd($domain);
        $node = $this->getFirst('domain:name', $domaincdNode);

        return in_array($node->getAttribute('avail'), ['1', 'true']);
    }

    /**
     * The reason why a domain is not available for creation.
     *
     * @param $domain fully qualified name of the domain object
     *
     * @return string
     */
    public function getReason($domain)
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
     * @return \DOMElement
     *
     * @throws UnexpectedValueException
     */
    protected function getDomaincd($domain)
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
