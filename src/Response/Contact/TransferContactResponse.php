<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of the contact transfer request.
 */
class TransferContactResponse extends CommonResponse
{
    /**
     * Server-unique identifier for the queried contact.
     */
    public function getIdentifier(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:id')->nodeValue;
    }

    /**
     * State of the most recent transfer request.
     */
    public function getTransferStatus(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:trStatus')->nodeValue;
    }

    /**
     * Identifier of the client that requested the object transfer.
     */
    public function getGainingRegistrar(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:reID')->nodeValue;
    }

    /**
     * The date and time that the transfer was requested as string.
     */
    public function getRequestDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:reDate')->nodeValue;
    }

    /**
     * The date and time that the transfer was requested as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getRequestDateAsObject(string $format): \DateTime
    {
        return date_create_from_format($format, $this->getRequestDate());
    }

    /**
     * The identifier of the client that should act upon the transfer request.
     */
    public function getLosingRegistrar(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:acID')->nodeValue;
    }

    /**
     * The date and time of a required or completed response as string. For a pending request, the value
     * identifies the date and time by which a response is required before an automated
     * response action will be taken by the server. For all other status types, the value
     * identifies the date and time when the request was completed.
     */
    public function getRequestExpiryDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:trnData/contact:acDate')->nodeValue;
    }

    /**
     * The date and time of a required or completed response as object. For a pending request, the value
     * identifies the date and time by which a response is required before an automated
     * response action will be taken by the server. For all other status types, the value
     * identifies the date and time when the request was completed.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getRequestExpiryDateAsObject(string $format): \DateTime
    {
        return date_create_from_format($format, $this->getRequestExpiryDate());
    }
}
