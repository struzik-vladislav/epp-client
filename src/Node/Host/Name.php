<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <host:name> node.
 */
class Name extends AbstractNode
{
    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'host:name', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['host']) || empty($parameters['host'])) {
            throw new InvalidArgumentException(sprintf('Missing parameters with a key \'host\''));
        }

        $this->getNode()->nodeValue = $parameters['host'];
    }
}
