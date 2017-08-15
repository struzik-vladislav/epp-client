<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;

/**
 * Object representation of the <contact:city> node.
 */
class City extends AbstractNode
{
    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'contact:city', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['city']) || empty($parameters['city'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'city\'.');
        }

        $this->getNode()->nodeValue = $parameters['city'];
    }
}
