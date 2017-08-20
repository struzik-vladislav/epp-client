<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <domain:curExpDate> node.
 */
class CurrentExpiryDate extends AbstractNode
{
    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'domain:curExpDate', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['expiry-date'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'expiry-date\'.');
        }

        if (!($parameters['expiry-date'] instanceof \DateTime)) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter with a key \'expiry-date\' must be instance of \'%s\' class.',
                \DateTime::class
            ));
        }

        $this->getNode()->nodeValue = $parameters['expiry-date']->format('Y-m-d');
    }
}
