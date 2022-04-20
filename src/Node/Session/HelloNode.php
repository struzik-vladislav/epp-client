<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Request\RequestInterface;

class HelloNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('hello');
        $parentNode->appendChild($node);

        return $node;
    }
}
