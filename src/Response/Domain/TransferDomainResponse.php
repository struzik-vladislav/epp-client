<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of the domain transfer request.
 */
class TransferDomainResponse extends CommonResponse
{
    /**
     * Fully qualified name of the domain object.
     */
    public function getDomain(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:name')->nodeValue;
    }

    /**
     * State of the most recent transfer request.
     */
    public function getTransferStatus(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:trStatus')->nodeValue;
    }

    /**
     * Identifier of the client that requested the object transfer.
     */
    public function getGainingRegistrar(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:reID')->nodeValue;
    }

    /**
     * The date and time that the transfer was requested as string.
     */
    public function getRequestDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:reDate')->nodeValue;
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
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:acID')->nodeValue;
    }

    /**
     * The date and time of a required or completed response as string. For a pending request, the value
     * identifies the date and time by which a response is required before an automated
     * response action will be taken by the server. For all other status types, the value
     * identifies the date and time when the request was completed.
     */
    public function getRequestExpiryDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:acDate')->nodeValue;
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

    /**
     * The end of the domain object's validity period as string if the <transfer> command caused
     * or causes a change in the validity period.
     */
    public function getExpiryDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:exDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The end of the domain object's validity period as object if the <transfer> command caused
     * or causes a change in the validity period.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getExpiryDateAsObject(string $format): ?\DateTime
    {
        $expiryDate = $this->getExpiryDate();
        if ($expiryDate === null) {
            return null;
        }

        return date_create_from_format($format, $expiryDate);
    }
}
