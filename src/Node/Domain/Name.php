<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <domain:name> node.
 */
class Name extends AbstractNode
{
    const HOSTS_ALL = 'all';
    const HOSTS_DEL = 'del';
    const HOSTS_SUB = 'sub';
    const HOSTS_NONE = 'none';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'domain:name', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['domain']) || empty($parameters['domain'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'domain\'.');
        }

        $this->getNode()->nodeValue = $parameters['domain'];

        if (isset($parameters['hosts'])) {
            if (!in_array($parameters['hosts'], [self::HOSTS_ALL, self::HOSTS_DEL, self::HOSTS_SUB, self::HOSTS_NONE])) {
                throw new UnexpectedValueException(sprintf(
                    'The value of the parameter with a key \'hosts\' must be set to \'%s\', \'%s\', \'%s\' or \'%s\'.',
                    self::HOSTS_ALL,
                    self::HOSTS_DEL,
                    self::HOSTS_SUB,
                    self::HOSTS_NONE
                ));
            }

            $this->getNode()->setAttribute('hosts', $parameters['hosts']);
        }
    }
}
