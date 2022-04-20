<?php

namespace Struzik\EPPClient\Response\Contact;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Response\CommonResponse;
use Struzik\EPPClient\Response\Contact\Helper\Disclose;
use Struzik\EPPClient\Response\Contact\Helper\PostalInfo;

/**
 * Object representation of the response of contact information command.
 */
class InfoContactResponse extends CommonResponse
{
    /**
     * Getting the identifier of the contact.
     */
    public function getIdentifier(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:id')->nodeValue;
    }

    /**
     * Repository object identifier.
     */
    public function getROIdentifier(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:roid')->nodeValue;
    }

    /**
     * List of object statuses.
     */
    public function getStatuses(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/contact:infData/contact:status');

        return array_map(static fn (\DOMNode $node): string => $node->getAttribute('s'), iterator_to_array($nodes));
    }

    /**
     * Check status existence.
     *
     * @param string $status string representation of the status
     */
    public function statusExist(string $status): bool
    {
        return in_array($status, $this->getStatuses(), true);
    }

    /**
     * Getting the postal-address information by type.
     *
     * @param string $type type of the <contact:postalInfo> node
     */
    public function getPostalInfo(string $type): ?PostalInfo
    {
        if (!in_array($type, [ContactPostalInfoNode::TYPE_INT, ContactPostalInfoNode::TYPE_LOC], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter \'type\' must be set to \'%s\' or \'%s\'.', ContactPostalInfoNode::TYPE_INT, ContactPostalInfoNode::TYPE_LOC));
        }

        $query = sprintf('//epp:epp/epp:response/epp:resData/contact:infData/contact:postalInfo[@type=\'%s\']', $type);
        $node = $this->getFirst($query);
        if ($node === null) {
            return null;
        }

        return new PostalInfo($this, $node);
    }

    /**
     * The contact's voice telephone number.
     */
    public function getVoice(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:voice');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The contact's facsimile telephone number.
     */
    public function getFax(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:fax');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The contact's email address.
     */
    public function getEmail(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:email')->nodeValue;
    }

    /**
     * The identifier of the sponsoring client.
     */
    public function getClientId(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:clID')->nodeValue;
    }

    /**
     * The identifier of the client that created the contact object.
     */
    public function getCreatorId(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:crID')->nodeValue;
    }

    /**
     * The date and time of contact-object creation as string.
     */
    public function getCreateDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:crDate')->nodeValue;
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

    /**
     * The identifier of the client that last updated the contact object.
     */
    public function getUpdaterId(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:upID');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent contact-object modification as string.
     */
    public function getUpdateDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:upDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent contact-object modification as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getUpdateDateAsObject(string $format): ?\DateTime
    {
        $updateDate = $this->getUpdateDate();
        if ($updateDate === null) {
            return null;
        }

        return date_create_from_format($format, $updateDate);
    }

    /**
     * The date and time of the most recent successful contact-object transfer as string.
     */
    public function getTransferDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:trDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent successful contact-object transfer as object.
     */
    public function getTransferDateAsObject(string $format): ?\DateTime
    {
        $transferDate = $this->getTransferDate();
        if ($transferDate === null) {
            return null;
        }

        return date_create_from_format($format, $transferDate);
    }

    /**
     * Authorization information associated with the contact object.
     */
    public function getAuthCode(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:authInfo/contact:pw');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The contact's disclosure preferences.
     */
    public function getDisclose(): ?Disclose
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/contact:infData/contact:disclose');
        if ($node === null) {
            return null;
        }

        return new Disclose($this, $node);
    }
}
