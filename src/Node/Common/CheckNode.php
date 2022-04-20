<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Request\RequestInterface;

class CheckNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('check');
        $parentNode->appendChild($node);

        return $node;
    }
}
