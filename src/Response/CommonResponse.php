<?php

namespace Struzik\EPPClient\Response;

/**
 * Extending the basic implementation for most response objects.
 */
class CommonResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess(): bool
    {
        return in_array($this->getResultCode(), [
            self::RC_SUCCESS,
            self::RC_SUCCESS_ACTION_PENDING,
            self::RC_SUCCESS_NO_MESSAGES,
            self::RC_SUCCESS_ACK_TO_DEQUEUE,
            self::RC_SUCCESS_ENDING_SESSION,
        ], true);
    }

    /**
     * A four-digit, decimal number that describes the success or failure of the command.
     */
    public function getResultCode(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:result')->getAttribute('code');
    }

    /**
     * Human-readable description of the response code.
     */
    public function getResultMessage(): string
    {
        $message = $this->getFirst('//epp:epp/epp:response/epp:result/epp:msg')->nodeValue;

        $nodes = $this->get('//epp:epp/epp:response/epp:result/epp:extValue/epp:value/*');
        $extValues = array_map(fn (\DOMNode $node) => $this->saveXML($node), iterator_to_array($nodes));

        $nodes = $this->get('//epp:epp/epp:response/epp:result/epp:extValue/epp:reason');
        $extReasons = array_map(fn (\DOMNode $node) => $this->saveXML($node), iterator_to_array($nodes));

        return implode("\n", [$message, ...$extValues, ...$extReasons]);
    }

    /**
     * The language of the response.
     */
    public function getResultMessageLang(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:result/epp:msg')->getAttribute('lang');
    }

    /**
     * Client transaction identifier.
     */
    public function getClientTransaction(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:trID/epp:clTRID')->nodeValue;
    }

    /**
     * Server transaction identifier.
     */
    public function getServerTransaction(): string
    {
        return $this->getFirst('//epp:epp/epp:response/epp:trID/epp:svTRID')->nodeValue;
    }
}
