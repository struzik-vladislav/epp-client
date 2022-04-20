<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class PollNode
{
    public const OPERATION_REQUEST = 'req';
    public const OPERATION_ACKNOWLEDGEMENT = 'ack';

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $operation, string $messageId = ''): \DOMElement
    {
        if (!in_array($operation, [self::OPERATION_REQUEST, self::OPERATION_ACKNOWLEDGEMENT], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter "operation" must be set to "%s" or "%s".', self::OPERATION_REQUEST, self::OPERATION_ACKNOWLEDGEMENT));
        }
        if ($operation === self::OPERATION_ACKNOWLEDGEMENT && $messageId === '') {
            throw new InvalidArgumentException('Invalid parameter "messageId".');
        }

        $node = $request->getDocument()->createElement('poll');
        $node->setAttribute('op', $operation);
        if ($operation === self::OPERATION_ACKNOWLEDGEMENT) {
            $node->setAttribute('msgID', $messageId);
        }
        $parentNode->appendChild($node);

        return $node;
    }
}
