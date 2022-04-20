<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Request\RequestInterface;

class DeleteNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('delete');
        $parentNode->appendChild($node);

        return $node;
    }
}
