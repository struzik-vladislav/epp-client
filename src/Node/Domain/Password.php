<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <domain:pw> node.
 */
class Password extends AbstractNode
{
    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'domain:pw', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['password']) || empty($parameters['password'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'password\'.');
        }

        $this->getNode()->nodeValue = $parameters['password'];
    }
}
