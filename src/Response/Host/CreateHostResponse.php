<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of host creating command.
 */
class CreateHostResponse extends CommonResponse
{
    /**
     * Fully qualified name of the host object.
     */
    public function getHost(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:creData/host:name')->nodeValue;
    }

    /**
     * Date and time of host object creation as string.
     */
    public function getCreateDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:creData/host:crDate')->nodeValue;
    }

    /**
     * Date and time of host object creation as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getCreateDateAsObject(string $format): \DateTime
    {
        return date_create_from_format($format, $this->getCreateDate());
    }
}
