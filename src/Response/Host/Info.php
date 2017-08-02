<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Response\AbstractCommonResponse;

/**
 * Object representation of the response of host information command.
 */
class Info extends AbstractCommonResponse
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
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:name');

        return $node->nodeValue;
    }

    /**
     * Unique identifier in the registry.
     *
     * @return string
     */
    public function getRepositoryId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:roid');

        return $node->nodeValue;
    }

    /**
     * List of object statuses.
     *
     * @return \Generator
     */
    public function getStatusList()
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/host:infData/host:status');

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
     * Getting a list of assigned IP addresses.
     *
     * @return \Generator
     */
    public function getIPList()
    {
        $nodes = $this->get('//epp:epp/epp:response/epp:resData/host:infData/host:addr');

        foreach ($nodes as $node) {
            yield (object) ['address' => $node->nodeValue, 'type' => $node->getAttribute('ip')];
        }
    }

    /**
     * The identifier of the registrar.
     *
     * @return string
     */
    public function getClientId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:clID');

        return $node->nodeValue;
    }

    /**
     * The identifier of the creator (registry).
     *
     * @return string
     */
    public function getCreatorId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:crID');

        return $node->nodeValue;
    }

    /**
     * Date and time of host object creation.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string
     */
    public function getCreateDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:crDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * The identifier of the updater (registry).
     *
     * @return string|null
     */
    public function getUpdaterId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:upID');
        if (is_null($node)) {
            return;
        }

        return $node->nodeValue;
    }

    /**
     * Date and time of host object updating.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getUpdateDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:upDate');
        if ($node === null) {
            return null;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * Date and time of host object transferring.
     *
     * @param string $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getTransferDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:resData/host:infData/host:trDate');
        if ($node === null) {
            return null;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }
}
