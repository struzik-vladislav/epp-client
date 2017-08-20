<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <domain:period> node.
 */
class Period extends AbstractNode
{
    const UNIT_YEAR = 'y';
    const UNIT_MONTH = 'm';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'domain:period', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['period'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'period\'.');
        }
        if (empty($parameters['period']) || !preg_match('/^\d+$/ui', $parameters['period'])) {
            throw new UnexpectedValueException('The value of the parameter with a key \'period\' must be integer number.');
        }

        $this->getNode()->nodeValue = $parameters['period'];

        if (!isset($parameters['unit'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'unit\'.');
        }
        if (!in_array($parameters['unit'], [self::UNIT_YEAR, self::UNIT_MONTH])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter with a key \'unit\' must be set to \'%s\' or \'%s\'.',
                self::UNIT_YEAR,
                self::UNIT_MONTH
            ));
        }

        $this->getNode()->setAttribute('unit', $parameters['unit']);
    }
}
