<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Request\RequestInterface;

class LogoutNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('logout');
        $parentNode->appendChild($node);

        return $node;
    }
}
