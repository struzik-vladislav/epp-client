<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <clID> node.
 */
class ClientId extends AbstractNode
{
    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'clID', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['login']) || empty($parameters['login'])) {
            throw new InvalidArgumentException(sprintf('Missing parameters with a key \'login\''));
        }

        $this->getNode()->nodeValue = $parameters['login'];
    }
}
