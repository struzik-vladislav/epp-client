<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Response\CommonResponse;
use Struzik\EPPClient\Node\Contact\PostalInfo;
use Struzik\EPPClient\Response\Contact\Helper\Disclose;
use Struzik\EPPClient\Response\Contact\Helper\PostalInfo as PostalInfoHelper;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the response of contact information command.
 */
class Info extends CommonResponse
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
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:id');

        return $node->nodeValue;
    }

    /**
     * Repository object identifier.
     *
     * @return string
     */
    public function getROIdentifier()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:roid');

        return $node->nodeValue;
    }

    /**
     * List of object statuses.
     *
     * @return \Generator
     */
    public function getStatusList()
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/contact:infData/contact:status');

        foreach ($nodes as $node) {
            yield $node->getAttribute('s');
        }
    }

    /**
     * Check status existence.
     *
     * @param string $status string representation of the status
     *
     * @return bool
     */
    public function statusExist($status)
    {
        $generator = $this->getStatusList();
        $list = iterator_to_array($generator);

        return in_array($status, $list);
    }

    /**
     * Getting the postal-address information by type.
     *
     * @param string $type type of the <contact:postalInfo> node
     *
     * @return PostalInfoHelper|null
     */
    public function getPostalInfo($type)
    {
        if (!in_array($type, [PostalInfo::TYPE_INT, PostalInfo::TYPE_LOC])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter \'type\' must be set to \'%s\' or \'%s\'.',
                PostalInfo::TYPE_INT,
                PostalInfo::TYPE_LOC
            ));
        }

        $query = sprintf('//epp:epp/epp:response/epp:resData/contact:infData/contact:postalInfo[@type=\'%s\']', $type);
        $node = $this->getFirst($query);

        return $node ? new PostalInfoHelper($this, $node) : null;
    }

    /**
     * The contact's voice telephone number.
     *
     * @return string
     */
    public function getVoice()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:voice');

        return $node->nodeValue;
    }

    /**
     * The contact's facsimile telephone number.
     *
     * @return string|null
     */
    public function getFax()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:fax');

        return $node ? $node->nodeValue : null;
    }

    /**
     * The contact's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:email');

        return $node->nodeValue;
    }

    /**
     * The identifier of the sponsoring client.
     *
     * @return string
     */
    public function getClientId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:clID');

        return $node->nodeValue;
    }

    /**
     * The identifier of the client that created the contact object.
     *
     * @return string
     */
    public function getCreatorId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:crID');

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
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:crDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * The identifier of the client that last updated the contact object.
     *
     * @return string|null
     */
    public function getUpdaterId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:upID');
        if (is_null($node)) {
            return;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent contact-object modification.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getUpdateDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:upDate');
        if ($node === null) {
            return null;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * The date and time of the most recent successful contact-object transfer.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getTransferDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:trDate');
        if ($node === null) {
            return null;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * Authorization information associated with the contact object.
     *
     * @return string|null
     */
    public function getAuthInfo()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:authInfo/contact:pw');

        return $node ? $node->nodeValue : null;
    }

    /**
     * The contact's disclosure preferences.
     *
     * @return Disclose|null
     */
    public function getDisclose()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:disclose');
        if ($node === null) {
            return null;
        }

        return new Disclose($this, $node);
    }
}
