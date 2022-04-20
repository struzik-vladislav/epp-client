<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of domain renewal command.
 */
class RenewDomainResponse extends CommonResponse
{
    /**
     * Fully qualified name of the domain object.
     */
    public function getDomain(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:renData/domain:name')->nodeValue;
    }

    /**
     * The date and time identifying the end of the domain object's registration period as string.
     */
    public function getExpiryDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:renData/domain:exDate');
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
