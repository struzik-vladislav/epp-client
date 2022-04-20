<?php

namespace Struzik\EPPClient\Response\Domain;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of domain information command.
 */
class InfoDomainResponse extends CommonResponse
{
    /**
     * Fully qualified name of the domain object.
     */
    public function getDomain(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:name')->nodeValue;
    }

    /**
     * Unique identifier in the registry.
     */
    public function getROIdentifier(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:roid')->nodeValue;
    }

    /**
     * List of object statuses.
     *
     * @return string[]
     */
    public function getStatuses(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/domain:infData/domain:status');

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
     * Nichandle of the domain registrant.
     */
    public function getRegistrant(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:registrant');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * Other nichandles with the types associated with the domain object.
     *
     * @return string[]
     */
    public function getContacts(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/domain:infData/domain:contact');
        $contacts = array_map(static fn (\DOMNode $node): array => ['type' => $node->getAttribute('type'), 'nichandle' => $node->nodeValue], iterator_to_array($nodes));

        return array_combine(array_column($contacts, 'type'), array_column($contacts, 'nichandle'));
    }

    /**
     * List of domain nameservers.
     *
     * @return string[]
     */
    public function getNameservers(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/domain:infData/domain:ns/domain:hostObj');

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * List of fully qualified names of the subordinate host objects that exist under this superordinate domain object.
     *
     * @return string[]
     */
    public function getHosts(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/domain:infData/domain:host');

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * The identifier of the sponsoring client.
     */
    public function getClientId(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:clID')->nodeValue;
    }

    /**
     * The identifier of the client that created the domain object.
     */
    public function getCreatorId(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:crID');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of domain object creation as string.
     */
    public function getCreateDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:crDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of domain object creation as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getCreateDateAsObject(string $format): ?\DateTime
    {
        $createDate = $this->getCreateDate();
        if ($createDate === null) {
            return null;
        }

        return date_create_from_format($format, $createDate);
    }

    /**
     * The date and time identifying the end of the domain object's registration period as string.
     */
    public function getExpiryDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:exDate');
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

    /**
     * The identifier of the client that last updated the domain object.
     */
    public function getUpdaterId(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:upID');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent domain object modification as string.
     */
    public function getUpdateDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:upDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent domain object modification as object.
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
     * The date and time of the most recent successful domain object transfer as string.
     */
    public function getTransferDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:trDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The date and time of the most recent successful domain object transfer as object.
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
     * Authorization information associated with the domain object.
     */
    public function getAuthCode(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/domain:infData/domain:authInfo/domain:pw');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }
}
