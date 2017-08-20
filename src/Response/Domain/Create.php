<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of domain creating command.
 */
class Create extends CommonResponse
{
    /**
     * Fully qualified name of the domain object.
     *
     * @return string
     */
    public function getDomain()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:creData/domain:name');

        return $node->nodeValue;
    }

    /**
     * The date and time of domain object creation.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string
     */
    public function getCreateDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:creData/domain:crDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * The date and time identifying the end of the domain object's registration period.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getExpiryDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:creData/domain:exDate');
        if ($node === null) {
            return;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }
}
