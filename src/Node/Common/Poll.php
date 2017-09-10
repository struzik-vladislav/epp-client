<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <poll> node.
 */
class Poll extends AbstractNode
{
    const OPERATION_REQUEST = 'req';
    const OPERATION_ACKNOWLEDGEMENT = 'ack';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'poll', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['operation'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'operation\'.');
        }

        if (!in_array($parameters['operation'], [self::OPERATION_REQUEST, self::OPERATION_ACKNOWLEDGEMENT])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter \'operation\' must be set to \'%s\' or \'%s\'.',
                self::OPERATION_REQUEST,
                self::OPERATION_ACKNOWLEDGEMENT
            ));
        }

        if ($parameters['operation'] === self::OPERATION_ACKNOWLEDGEMENT
            && (!isset($parameters['message-id']) || empty($parameters['message-id']))
        ) {
            throw new InvalidArgumentException('Missing parameter with a key \'message-id\'.');
        }

        $this->getNode()->setAttribute('op', $parameters['operation']);
        if ($parameters['operation'] === self::OPERATION_ACKNOWLEDGEMENT) {
            $this->getNode()->setAttribute('msgID', $parameters['message-id']);
        }
    }
}
