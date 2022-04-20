<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Request\RequestInterface;

class DomainRemoveNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('domain:rem');
        $parentNode->appendChild($node);

        return $node;
    }
}
