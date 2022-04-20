<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Request\RequestInterface;

class CommandNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('command');
        $parentNode->appendChild($node);

        return $node;
    }
}
