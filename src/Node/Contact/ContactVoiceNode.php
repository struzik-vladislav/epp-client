<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactVoiceNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $voice): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:voice', $voice);
        $parentNode->appendChild($node);

        return $node;
    }
}
