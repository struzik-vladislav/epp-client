<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Response\CommonResponse;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use XPath;

/**
 * Object representation of the response of contact checking command.
 */
class Check extends CommonResponse
{
    /**
     * Contact is available for creating.
     *
     * @param string $contactId contact identifier
     *
     * @return bool
     */
    public function isAvailable($contactId)
    {
        $contactcdNode = $this->getContactcd($contactId);
        $node = $this->getFirst('contact:id', $contactcdNode);

        return in_array($node->getAttribute('avail'), ['1', 'true']);
    }

    /**
     * The reason why a contact is not available for creation.
     *
     * @param string $contactId contact identifier
     *
     * @return string
     */
    public function getReason($contactId)
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
     * @return \DOMElement
     *
     * @throws UnexpectedValueException
     */
    protected function getContactcd($contactId)
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
