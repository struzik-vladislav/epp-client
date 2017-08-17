<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of contact creating command.
 */
class Create extends CommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS;
    }

    /**
     * Getting the identifier of the contact.
     *
     * @return string
     */
    public function getIdentifier()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:creData/contact:id');

        return $node->nodeValue;
    }

    /**
     * The date and time of contact-object creation.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string
     */
    public function getCreateDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:creData/contact:crDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }
}
