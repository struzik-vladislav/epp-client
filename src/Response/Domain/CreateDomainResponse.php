<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of domain creating command.
 */
class CreateDomainResponse extends CommonResponse
{
    /**
     * Fully qualified name of the domain object.
     */
    public function getDomain(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:creData/domain:name')->nodeValue;
    }

    /**
     * The date and time of domain object creation as string.
     */
    public function getCreateDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:creData/domain:crDate')->nodeValue;
    }

    /**
     * The date and time of domain object creation as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getCreateDateAsObject(string $format): \DateTime
    {
        return date_create_from_format($format, $this->getCreateDate());
    }

    /**
     * The date and time identifying the end of the domain object's registration period as string.
     */
    public function getExpiryDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:creData/domain:exDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time identifying the end of the domain object's registration period as object.
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
