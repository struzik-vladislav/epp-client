<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class TransferNode
{
    public const OPERATION_REQUEST = 'request';
    public const OPERATION_CANCEL = 'cancel';
    public const OPERATION_APPROVE = 'approve';
    public const OPERATION_REJECT = 'reject';
    public const OPERATION_QUERY = 'query';
    public const OPERATIONS = [
        self::OPERATION_REQUEST,
        self::OPERATION_CANCEL,
        self::OPERATION_APPROVE,
        self::OPERATION_REJECT,
        self::OPERATION_QUERY,
    ];

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $operation): \DOMElement
    {
        if ($operation === '') {
            throw new InvalidArgumentException('Invalid parameter "operation".');
        }
        if (!in_array($operation, self::OPERATIONS, true)) {
            throw new UnexpectedValueException('Invalid value of the parameter "operation".');
        }

        $node = $request->getDocument()->createElement('transfer');
        $node->setAttribute('op', $operation);
        $parentNode->appendChild($node);

        return $node;
    }
}
