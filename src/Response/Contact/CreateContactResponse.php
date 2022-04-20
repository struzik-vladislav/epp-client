<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of contact creating command.
 */
class CreateContactResponse extends CommonResponse
{
    /**
     * Getting the identifier of the contact.
     */
    public function getIdentifier(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:creData/contact:id')->nodeValue;
    }

    /**
     * The date and time of contact-object creation as string.
     */
    public function getCreateDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:creData/contact:crDate')->nodeValue;
    }

    /**
     * The date and time of contact-object creation as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getCreateDateAsObject(string $format): \DateTime
    {
        return date_create_from_format($format, $this->getCreateDate());
    }
}
