<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Request\RequestInterface;

class OptionsNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('options');
        $parentNode->appendChild($node);

        return $node;
    }
}
