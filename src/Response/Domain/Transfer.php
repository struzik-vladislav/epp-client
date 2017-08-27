<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of the domain transfer request.
 */
class Transfer extends CommonResponse
{
    /**
     * Fully qualified name of the domain object.
     *
     * @return string
     */
    public function getDomain()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:name');

        return $node->nodeValue;
    }

    /**
     * State of the most recent transfer request.
     *
     * @return string
     */
    public function getTransferStatus()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:trStatus');

        return $node->nodeValue;
    }

    /**
     * Identifier of the client that requested the object transfer.
     *
     * @return string
     */
    public function getGainingRegistrar()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:reID');

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
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:reDate');
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
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:acID');

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
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:acDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * The end of the domain object's validity period if the <transfer> command caused
     * or causes a change in the validity period.
     *
     * @param string|null $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getExpiryDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:trnData/domain:exDate');
        if ($node === null) {
            return;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }
}
