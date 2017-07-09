<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <host:addr> node.
 */
class Address extends AbstractNode
{
    const IP_V4 = 'v4';
    const IP_V6 = 'v6';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'host:addr', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['address']) || empty($parameters['address'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'address\'.');
        }

        if (filter_var($parameters['address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $type = self::IP_V4;
        } elseif (filter_var($parameters['address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $type = self::IP_V6;
        } else {
            throw new UnexpectedValueException('The value of the parameter \'address\' must be a valid IPv4 or IPv6 address.');
        }

        $this->getNode()->nodeValue = $parameters['address'];
        $this->getNode()->setAttribute('ip', $type);
    }
}
