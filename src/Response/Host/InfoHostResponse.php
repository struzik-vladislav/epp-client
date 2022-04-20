<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of host information command.
 */
class InfoHostResponse extends CommonResponse
{
    /**
     * Fully qualified name of the host object.
     */
    public function getHost(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:name')->nodeValue;
    }

    /**
     * Unique identifier in the registry.
     */
    public function getROIdentifier(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:roid')->nodeValue;
    }

    /**
     * List of object statuses.
     *
     * @return string[]
     */
    public function getStatuses(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/host:infData/host:status');

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
     * Getting a list of assigned IP addresses.
     *
     * @return string[]
     */
    public function getAddresses(): array
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/host:infData/host:addr');

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * The identifier of the registrar.
     */
    public function getClientId(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:clID')->nodeValue;
    }

    /**
     * The identifier of the creator (registry).
     */
    public function getCreatorId(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:crID')->nodeValue;
    }

    /**
     * Date and time of host object creation as string.
     */
    public function getCreateDate(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:crDate')->nodeValue;
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

    /**
     * The identifier of the updater (registry).
     */
    public function getUpdaterId(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:upID');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * Date and time of host object updating as string.
     */
    public function getUpdateDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:upDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * Date and time of host object updating as string.
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
     * Date and time of host object transferring as string.
     */
    public function getTransferDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:trDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * Date and time of host object transferring as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getTransferDateAsObject(string $format): ?\DateTime
    {
        $transferDate = $this->getTransferDate();
        if ($transferDate === null) {
            return null;
        }

        return date_create_from_format($format, $transferDate);
    }
}
