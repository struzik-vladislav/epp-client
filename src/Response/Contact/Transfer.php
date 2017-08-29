<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of the contact transfer request.
 */
class Transfer extends CommonResponse
{
    /**
     * Server-unique identifier for the queried contact.
     *
     * @return string
     */
    public function getIdentifier()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:id');

        return $node->nodeValue;
    }

    /**
     * State of the most recent transfer request.
     *
     * @return string
     */
    public function getTransferStatus()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:trStatus');

        return $node->nodeValue;
    }

    /**
     * Identifier of the client that requested the object transfer.
     *
     * @return string
     */
    public function getGainingRegistrar()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:reID');

        return $node->nodeValue;
    }

    /**
     * The date and time that the transfer was requested.
     *
     * @param string|null $format format of the date string
     *
     * @return \DateTime|string
     */
    public function getRequestDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:reDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * The identifier of the client that should act upon the transfer request.
     *
     * @return string
     */
    public function getLosingRegistrar()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:acID');

        return $node->nodeValue;
    }

    /**
     * The date and time of a required or completed response. For a pending request, the value
     * identifies the date and time by which a response is required before an automated
     * response action will be taken by the server. For all other status types, the value
     * identifies the date and time when the request was completed.
     *
     * @param string|null $format format of the date string
     *
     * @return \DateTime|string
     */
    public function getRequestExpiryDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:acDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }
}
