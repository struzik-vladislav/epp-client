<?php

namespace Struzik\EPPClient\Response\Poll;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the responses of the message queue commands.
 */
class PollResponse extends CommonResponse
{
    /**
     * Number of messages that exist in the queue.
     */
    public function getMessageCount(): ?int
    {
        if ($this->getResultCode() === self::RC_SUCCESS_NO_MESSAGES) {
            return 0;
        }

        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ');
        if ($node === null) {
            return null;
        }

        return (int) $node->getAttribute('count');
    }

    /**
     * Unique identifier of the message at the head of the queue.
     */
    public function getMessageId(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ');
        if ($node === null) {
            return null;
        }

        return $node->getAttribute('id');
    }

    /**
     * Date and time that the message was enqueued as string.
     */
    public function getEnqueuedDate(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ/epp:qDate');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * Date and time that the message was enqueued as object.
     *
     * @param string $format format for creating \DateTime object
     */
    public function getEnqueuedDateAsObject(string $format): ?\DateTime
    {
        $enqueuedDate = $this->getEnqueuedDate();
        if ($enqueuedDate === null) {
            return null;
        }

        return date_create_from_format($format, $enqueuedDate);
    }

    /**
     * A human-readable message.
     */
    public function getEnqueuedMessage(): ?string
    {
        $node = $this->getFirst('//epp:epp/epp:response/epp:msgQ/epp:msg');
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }
}
