<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Request\RequestInterface;

class TransactionIdNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $identifier = $request->getClient()->getIdGenerator()->generate($request);

        $node = $request->getDocument()->createElement('clTRID', $identifier);
        $parentNode->appendChild($node);

        return $node;
    }
}
