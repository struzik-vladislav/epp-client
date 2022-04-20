<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Request\RequestInterface;

class CreateNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('create');
        $parentNode->appendChild($node);

        return $node;
    }
}
