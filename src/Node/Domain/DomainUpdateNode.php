<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Request\RequestInterface;

class DomainUpdateNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $namespace = $request->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_DOMAIN);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the domain namespace cannot be empty.');
        }

        $node = $request->getDocument()->createElement('domain:update');
        $node->setAttribute('xmlns:domain', $namespace);
        $parentNode->appendChild($node);

        return $node;
    }
}
