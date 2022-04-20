<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Request\RequestInterface;

class HostDeleteNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $namespace = $request->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_HOST);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the host namespace cannot be empty.');
        }

        $node = $request->getDocument()->createElement('host:delete');
        $node->setAttribute('xmlns:host', $namespace);
        $parentNode->appendChild($node);

        return $node;
    }
}
