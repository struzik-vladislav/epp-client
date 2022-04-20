<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainContactNode
{
    public const TYPE_ADMIN = 'admin';
    public const TYPE_BILLING = 'billing';
    public const TYPE_TECH = 'tech';

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $type, string $contact): \DOMElement
    {
        if ($contact === '') {
            throw new InvalidArgumentException('Invalid parameter "contact".');
        }
        if ($type === '') {
            throw new InvalidArgumentException('Invalid parameter "type".');
        }
        if (!in_array($type, [self::TYPE_ADMIN, self::TYPE_BILLING, self::TYPE_TECH], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter "type" must be set to "%s", "%s" or "%s".', self::TYPE_ADMIN, self::TYPE_BILLING, self::TYPE_TECH));
        }

        $node = $request->getDocument()->createElement('domain:contact', $contact);
        $node->setAttribute('type', $type);
        $parentNode->appendChild($node);

        return $node;
    }
}
