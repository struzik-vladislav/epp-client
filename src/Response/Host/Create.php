<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Response\AbstractCommonResponse;

/**
 * Object representation of the response of host creating command.
 */
class Create extends AbstractCommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS;
    }

    /**
     * Fully qualified name of the host object.
     *
     * @return string
     */
    public function getHost()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:creData/host:name');

        return $node->nodeValue;
    }

    /**
     * Date and time of host object creation.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime | null
     */
    public function getCreateDate($format)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:creData/host:crDate');
        if ($node) {
            return \DateTime::createFromFormat($format, $node->nodeValue);
        }

        return;
    }
}
