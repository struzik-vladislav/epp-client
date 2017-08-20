<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <domain:contact> node.
 */
class Contact extends AbstractNode
{
    const TYPE_ADMIN = 'admin';
    const TYPE_BILLING = 'billing';
    const TYPE_TECH = 'tech';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'domain:contact', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['contact']) || empty($parameters['contact'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'contact\'.');
        }

        if (!isset($parameters['type'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'type\'.');
        }
        if (!in_array($parameters['type'], [self::TYPE_ADMIN, self::TYPE_BILLING, self::TYPE_TECH])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter with a key \'type\' must be set to \'%s\', \'%s\' or \'%s\'.',
                self::TYPE_ADMIN,
                self::UNIT_MONTH,
                self::TYPE_TECH
            ));
        }

        $this->getNode()->nodeValue = $parameters['contact'];
        $this->getNode()->setAttribute('type', $parameters['type']);
    }
}
