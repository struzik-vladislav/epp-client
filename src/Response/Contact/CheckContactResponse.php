<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\CommonResponse;
use XPath;

/**
 * Object representation of the response of contact checking command.
 */
class CheckContactResponse extends CommonResponse
{
    /**
     * Contact is available for creating.
     *
     * @param string $contactId contact identifier
     */
    public function isAvailable(string $contactId): bool
    {
        $contactcdNode = $this->getContactcd($contactId);
        $node = $this->getFirst('contact:id', $contactcdNode);

        return in_array($node->getAttribute('avail'), ['1', 'true'], true);
    }

    /**
     * The reason why a contact is not available for creation.
     *
     * @param string $contactId contact identifier
     */
    public function getReason(string $contactId): ?string
    {
        $contactcdNode = $this->getContactcd($contactId);
        $node = $this->getFirst('contact:reason', $contactcdNode);

        return $node === null ? null : $node->nodeValue;
    }

    /**
     * Getting the <contact:cd> node by identifier.
     *
     * @param string $contactId contact identifier
     *
     * @throws UnexpectedValueException
     */
    protected function getContactcd(string $contactId): \DOMNode
    {
        $pattern = '//epp:epp/epp:response/epp:resData/contact:chkData/contact:cd[contact:id[php:functionString("XPath\quote", text()) = \'%s\']]';
        $query = sprintf($pattern, XPath\quote($contactId));
        $node = $this->getFirst($query);

        if ($node === null) {
            throw new UnexpectedValueException(sprintf('Contact with ID [%s] not found.', $contactId));
        }

        return $node;
    }
}
