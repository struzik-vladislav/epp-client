<?php

namespace Struzik\EPPClient\Response\Session;

use Struzik\EPPClient\Response\AbstractResponse;

/**
 * Object representation of the EPP greeting.
 */
class Greeting extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        $node = $this->getFirst('//epp:epp/epp:greeting');

        return $node !== null;
    }

    /**
     * Name of the server.
     *
     * @return string
     */
    public function getServerId()
    {
        $node = $this->getFirst('//epp:epp/epp:greeting/epp:svID');

        return $node->nodeValue;
    }

    /**
     * Server's current date and time.
     *
     * @param string $format format for creating \DateTime object
     *
     * @return string|\DateTime
     */
    public function getServerDate($format = null)
    {
        $node = $this->getFirst('//epp:epp/epp:greeting/epp:svDate');
        if ($format === null) {
            return $node->nodeValue;
        }

        return \DateTime::createFromFormat($format, $node->nodeValue);
    }

    /**
     * Protocol versions supported by the server.
     *
     * @return \Generator
     */
    public function getVersions()
    {
        $nodes = $this->getFirst('//epp:epp/epp:greeting/epp:svcMenu/epp:version');

        foreach ($nodes as $node) {
            yield $node->nodeValue;
        }
    }

    /**
     * Identifiers of the text response languages known by the server.
     *
     * @return \Generator
     */
    public function getLanguages()
    {
        $nodes = $this->get('//epp:epp/epp:greeting/epp:svcMenu/epp:lang');

        foreach ($nodes as $node) {
            yield $node->nodeValue;
        }
    }

    /**
     * Namespace URIs representing the objects that the server is capable of managing.
     *
     * @return \Generator
     */
    public function getNamespaceURIs()
    {
        $nodes = $this->get('//epp:epp/epp:greeting/epp:svcMenu/epp:objURI');

        foreach ($nodes as $node) {
            yield $node->nodeValue;
        }
    }

    /**
     * Namespace URIs representing object extensions supported by the server.
     *
     * @return \Generator
     */
    public function getExtNamespaceURIs()
    {
        $nodes = $this->get('//epp:epp/epp:greeting/epp:svcMenu/epp:svcExtension/epp:extURI');

        foreach ($nodes as $node) {
            yield $node->nodeValue;
        }
    }

    /**
     * Getting data collection policy node.
     *
     * @return \DOMNode|null
     */
    public function getDCP()
    {
        return $this->getFirst('//epp:epp/epp:greeting/epp:dcp');
    }
}
