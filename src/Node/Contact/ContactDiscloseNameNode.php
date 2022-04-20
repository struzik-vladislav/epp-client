<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactDiscloseNameNode
{
    public const TYPE_LOC = 'loc';
    public const TYPE_INT = 'int';

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $type): \DOMElement
    {
        if ($type === '') {
            throw new InvalidArgumentException('Invalid parameter "type".');
        }
        if (!in_array($type, [self::TYPE_INT, self::TYPE_LOC], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter with a key "flag" must be set to "%s" or "%s".', self::TYPE_INT, self::TYPE_LOC));
        }

        $node = $request->getDocument()->createElement('contact:name');
        $node->setAttribute('type', $type);
        $parentNode->appendChild($node);

        return $node;
    }
}
