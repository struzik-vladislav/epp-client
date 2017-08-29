<?php

namespace Struzik\EPPClient\Response\Poll;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the responses of the message queue commands.
 */
class Poll extends CommonResponse
{
    /**
     * Number of messages that exist in the queue.
     *
     * @return string|null
     */
    public function getMessageCount()
    {
        if ($this->getResultCode() === self::RC_SUCCESS_NO_MESSAGES) {
            return '0';
        }

        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ');
        if ($node === null) {
            return null;
        }

        return $node->getAttribute('count');
    }

    /**
     * Unique identifier of the message at the head of the queue.
     *
     * @return string|null
     */
    public function getMessageId()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ');
        if ($node === null) {
            return null;
        }

        return $node->getAttribute('id');
    }

    /**
     * Date and time that the message was enqueued.
     *
     * @param string|null $format format of the date string
     *
     * @return \DateTime|string|null
     */
    public function getEnqueuedDate()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ/epp:qDate');
        if ($node === null) {
            return;
        }

        if ($format === null) {
            return $node->nodeValue;
        }

        return date_create_from_format($format, $node->nodeValue);
    }

    /**
     * A human-readable message.
     *
     * @return string|null
     */
    public function getEnqueuedMessage()
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ/epp:msg');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }
}
