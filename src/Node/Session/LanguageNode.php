<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class LanguageNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $language): \DOMElement
    {
        if ($language === '') {
            throw new InvalidArgumentException('Invalid parameter "language".');
        }

        $node = $request->getDocument()->createElement('lang', $language);
        $parentNode->appendChild($node);

        return $node;
    }
}
