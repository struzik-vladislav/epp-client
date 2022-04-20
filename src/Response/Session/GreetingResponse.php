<?php

namespace Struzik\EPPClient\Response\Session;

use Struzik\EPPClient\Response\AbstractResponse;

/**
 * Object representation of the EPP greeting.
 */
class GreetingResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess(): bool
    {
        return $this->getFirst('//epp:epp/epp:greeting') !== null;
    }

    /**
     * Name of the server.
     */
    public function getServerId(): string
    {
        return $this->getFirst('//epp:epp/epp:greeting/epp:svID')->nodeValue;
    }

    /**
     * Server's current date and time as string.
     */
    public function getServerDate(): string
    {
        return $this->getFirst('//epp:epp/epp:greeting/epp:svDate')->nodeValue;
    }

    /**
     * Server's current date and time as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getServerDateAsObject(string $format): \DateTime
    {
        return date_create_from_format($format, $this->getServerDate());
    }

    /**
     * Protocol versions supported by the server.
     */
    public function getVersion(): string
    {
        return $this->getFirst('//epp:epp/epp:greeting/epp:svcMenu/epp:version')->nodeValue;
    }

    /**
     * Identifiers of the text response languages known by the server.
     *
     * @return string[]
     */
    public function getLanguages(): array
    {
        $nodes = $this->get('//epp:epp/epp:greeting/epp:svcMenu/epp:lang');

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * Namespace URIs representing the objects that the server is capable of managing.
     *
     * @return string[]
     */
    public function getNamespaceURIs(): array
    {
        $nodes = $this->get('//epp:epp/epp:greeting/epp:svcMenu/epp:objURI');

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * Namespace URIs representing object extensions supported by the server.
     *
     * @return string[]
     */
    public function getExtNamespaceURIs(): array
    {
        $nodes = $this->get('//epp:epp/epp:greeting/epp:svcMenu/epp:svcExtension/epp:extURI');

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * Getting data collection policy node.
     */
    public function getDCP(): ?\DOMNode
    {
        return $this->getFirst('//epp:epp/epp:greeting/epp:dcp');
    }
}
