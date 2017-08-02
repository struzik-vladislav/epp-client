<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <contact:status> node.
 */
class Status extends AbstractNode
{
    const STATUS_CLIENT_DELETE_PROHIBITED = 'clientDeleteProhibited';
    const STATUS_CLIENT_TRANSFER_PROHIBITED = 'clientTransferProhibited';
    const STATUS_CLIENT_UPDATE_PROHIBITED = 'clientUpdateProhibited';
    const STATUS_LINKED = 'linked';
    const STATUS_OK = 'ok';
    const STATUS_PENDING_CREATE = 'pendingCreate';
    const STATUS_PENDING_DELETE = 'pendingDelete';
    const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    const STATUS_PENDING_UPDATE = 'pendingUpdate';
    const STATUS_SERVER_DELETE_PROHIBITED = 'serverDeleteProhibited';
    const STATUS_SERVER_TRANSFER_PROHIBITED = 'serverTransferProhibited';
    const STATUS_SERVER_UPDATE_PROHIBITED = 'serverUpdateProhibited';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'contact:status', $parameters);
    }

    /**
     * Getting the array of the allowed statuses.
     *
     * @return array
     */
    public function getAllowedStatuses()
    {
        return [
            self::STATUS_CLIENT_DELETE_PROHIBITED,
            self::STATUS_CLIENT_TRANSFER_PROHIBITED,
            self::STATUS_CLIENT_UPDATE_PROHIBITED,
            self::STATUS_LINKED,
            self::STATUS_OK,
            self::STATUS_PENDING_CREATE,
            self::STATUS_PENDING_DELETE,
            self::STATUS_PENDING_TRANSFER,
            self::STATUS_PENDING_UPDATE,
            self::STATUS_SERVER_DELETE_PROHIBITED,
            self::STATUS_SERVER_TRANSFER_PROHIBITED,
            self::STATUS_SERVER_UPDATE_PROHIBITED,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['status']) || empty($parameters['status'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'status\'.');
        }

        if (!in_array($parameters['status'], $this->getAllowedStatuses())) {
            throw new UnexpectedValueException('Invalid value of the parameter with a key \'status\'.');
        }

        $this->getNode()->setAttribute('s', $parameters['status']);
    }
}
