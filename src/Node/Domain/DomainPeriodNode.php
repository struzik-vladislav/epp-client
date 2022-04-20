<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainPeriodNode
{
    public const UNIT_YEAR = 'y';
    public const UNIT_MONTH = 'm';

    public static function create(RequestInterface $request, \DOMElement $parentNode, int $period, string $unit): \DOMElement
    {
        if ($period < 0) {
            throw new UnexpectedValueException('The value of the parameter "period" must be integer number and great that or equal zero.');
        }
        if ($unit === '') {
            throw new InvalidArgumentException('Invalid parameter "unit".');
        }
        if (!in_array($unit, [self::UNIT_YEAR, self::UNIT_MONTH], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter "unit" must be set to "%s" or "%s".', self::UNIT_YEAR, self::UNIT_MONTH));
        }

        $node = $request->getDocument()->createElement('domain:period', $period);
        $node->setAttribute('unit', $unit);
        $parentNode->appendChild($node);

        return $node;
    }
}
