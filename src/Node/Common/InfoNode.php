<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Request\RequestInterface;

class InfoNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('info');
        $parentNode->appendChild($node);

        return $node;
    }
}
