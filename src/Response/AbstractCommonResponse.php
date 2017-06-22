<?php

namespace Struzik\EPPClient\Response;

/**
 * Extending the basic implementation for most response objects.
 */
abstract class AbstractCommonResponse extends AbstractResponse
{
    /**
     * A four-digit, decimal number that describes the success or failure of the command.
     *
     * @return string
     */
    public function getResultCode()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:result');

        return $node->getAttribute('code');
    }

    /**
     * Human-readable description of the response code.
     *
     * @return string
     */
    public function getResultMessage()
    {
        $parts = [];

        $node = $this->getFirst('//epp:epp/epp:response/epp:result/epp:msg');
        $parts[] = $node->nodeValue;

        $nodes = $this->get('//epp:epp/epp:response/epp:result/epp:extValue/epp:value/*');
        foreach ($nodes as $node) {
            $parts[] = $this->saveXML($node);
        }

        $nodes = $this->get('//epp:epp/epp:response/epp:result/epp:extValue/epp:reason');
        foreach ($nodes as $node) {
            $parts[] = $node->nodeValue;
        }

        return implode("\n", $parts);
    }

    /**
     * The language of the response.
     *
     * @return string
     */
    public function getResultMessageLang()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:result/epp:msg');

        return $node->getAttribute('lang');
    }

    /**
     * Client transaction identifier.
     *
     * @return string
     */
    public function getClientTransaction()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:trID/epp:clTRID');

        return $node->nodeValue;
    }

    /**
     * Server transaction identifier.
     *
     * @return string
     */
    public function getServerTransaction()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:trID/epp:svTRID');

        return $node->nodeValue;
    }
}
