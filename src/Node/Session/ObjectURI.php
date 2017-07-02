<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <objURI> node.
 */
class ObjectURI extends AbstractNode
{
    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'objURI', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['uri']) || empty($parameters['uri'])) {
            throw new InvalidArgumentException(sprintf('Missing parameters with a key \'uri\''));
        }

        $this->getNode()->nodeValue = $parameters['uri'];
    }
}
