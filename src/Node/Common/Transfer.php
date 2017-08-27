<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <transfer> node.
 */
class Transfer extends AbstractNode
{
    const OPERATION_REQUEST = 'request';
    const OPERATION_CANCEL = 'cancel';
    const OPERATION_APPROVE = 'approve';
    const OPERATION_REJECT = 'reject';
    const OPERATION_QUERY = 'query';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'transfer', $parameters);
    }

    /**
     * Getting the array of the allowed operations.
     *
     * @return array
     */
    public function getAllowedOperations()
    {
        return [
            self::OPERATION_REQUEST,
            self::OPERATION_CANCEL,
            self::OPERATION_APPROVE,
            self::OPERATION_REJECT,
            self::OPERATION_QUERY,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['operation'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'operation\'.');
        }

        if (!in_array($parameters['operation'], $this->getAllowedOperations())) {
            throw new UnexpectedValueException('Invalid value of the parameter with a key \'operation\'.');
        }

        $this->getNode()->setAttribute('op', $parameters['operation']);
    }
}
