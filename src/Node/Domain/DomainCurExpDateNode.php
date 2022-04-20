<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Request\RequestInterface;

class DomainCurExpDateNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, \DateTimeInterface $curExpDate): \DOMElement
    {
        $node = $request->getDocument()->createElement('domain:curExpDate', $curExpDate->format('Y-m-d'));
        $parentNode->appendChild($node);

        return $node;
    }
}
