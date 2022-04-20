<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class HostStatusNode
{
    public const STATUS_CLIENT_DELETE_PROHIBITED = 'clientDeleteProhibited';
    public const STATUS_CLIENT_UPDATE_PROHIBITED = 'clientUpdateProhibited';
    public const STATUS_LINKED = 'linked';
    public const STATUS_OK = 'ok';
    public const STATUS_PENDING_CREATE = 'pendingCreate';
    public const STATUS_PENDING_DELETE = 'pendingDelete';
    public const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    public const STATUS_PENDING_UPDATE = 'pendingUpdate';
    public const STATUS_SERVER_DELETE_PROHIBITED = 'serverDeleteProhibited';
    public const STATUS_SERVER_UPDATE_PROHIBITED = 'serverUpdateProhibited';
    public const STATUSES = [
        self::STATUS_CLIENT_DELETE_PROHIBITED,
        self::STATUS_CLIENT_UPDATE_PROHIBITED,
        self::STATUS_LINKED,
        self::STATUS_OK,
        self::STATUS_PENDING_CREATE,
        self::STATUS_PENDING_DELETE,
        self::STATUS_PENDING_TRANSFER,
        self::STATUS_PENDING_UPDATE,
        self::STATUS_SERVER_DELETE_PROHIBITED,
        self::STATUS_SERVER_UPDATE_PROHIBITED,
    ];

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $status): \DOMElement
    {
        if ($status === '') {
            throw new InvalidArgumentException('Invalid parameter "status".');
        }
        if (!in_array($status, self::STATUSES, true)) {
            throw new UnexpectedValueException('Invalid value of the parameter "status".');
        }

        $node = $request->getDocument()->createElement('host:status');
        $node->setAttribute('s', $status);
        $parentNode->appendChild($node);

        return $node;
    }
}
