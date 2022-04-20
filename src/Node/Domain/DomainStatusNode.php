<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainStatusNode
{
    public const STATUS_CLIENT_DELETE_PROHIBITED = 'clientDeleteProhibited';
    public const STATUS_CLIENT_HOLD = 'clientHold';
    public const STATUS_CLIENT_RENEW_PROHIBITED = 'clientRenewProhibited';
    public const STATUS_CLIENT_TRANSFER_PROHIBITED = 'clientTransferProhibited';
    public const STATUS_CLIENT_UPDATE_PROHIBITED = 'clientUpdateProhibited';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_OK = 'ok';
    public const STATUS_PENDING_CREATE = 'pendingCreate';
    public const STATUS_PENDING_DELETE = 'pendingDelete';
    public const STATUS_PENDING_RENEW = 'pendingRenew';
    public const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    public const STATUS_PENDING_UPDATE = 'pendingUpdate';
    public const STATUS_SERVER_DELETE_PROHIBITED = 'serverDeleteProhibited';
    public const STATUS_SERVER_HOLD = 'serverHold';
    public const STATUS_SERVER_RENEW_PROHIBITED = 'serverRenewProhibited';
    public const STATUS_SERVER_TRANSFER_PROHIBITED = 'serverTransferProhibited';
    public const STATUS_SERVER_UPDATE_PROHIBITED = 'serverUpdateProhibited';
    public const STATUSES = [
        self::STATUS_CLIENT_DELETE_PROHIBITED,
        self::STATUS_CLIENT_HOLD,
        self::STATUS_CLIENT_RENEW_PROHIBITED,
        self::STATUS_CLIENT_TRANSFER_PROHIBITED,
        self::STATUS_CLIENT_UPDATE_PROHIBITED,
        self::STATUS_INACTIVE,
        self::STATUS_OK,
        self::STATUS_PENDING_CREATE,
        self::STATUS_PENDING_DELETE,
        self::STATUS_PENDING_RENEW,
        self::STATUS_PENDING_TRANSFER,
        self::STATUS_PENDING_UPDATE,
        self::STATUS_SERVER_DELETE_PROHIBITED,
        self::STATUS_SERVER_HOLD,
        self::STATUS_SERVER_RENEW_PROHIBITED,
        self::STATUS_SERVER_TRANSFER_PROHIBITED,
        self::STATUS_SERVER_UPDATE_PROHIBITED,
    ];

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $status, string $reason = '', string $language = ''): \DOMElement
    {
        if ($status === '') {
            throw new InvalidArgumentException('Invalid parameter "status".');
        }
        if (!in_array($status, self::STATUSES, true)) {
            throw new UnexpectedValueException('Invalid value of the parameter "status".');
        }

        $node = $request->getDocument()->createElement('domain:status');
        $node->setAttribute('s', $status);
        $parentNode->appendChild($node);

        if ($reason !== '' && $language !== '') {
            $node->nodeValue = $reason;
            $node->setAttribute('lang', $language);
        }

        return $node;
    }
}
