<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Request\RequestInterface;

class EppNode
{
    public static function create(RequestInterface $request): \DOMElement
    {
        $namespace = $request->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_ROOT);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the root namespace cannot be empty.');
        }

        $node = $request->getDocument()->createElement('epp');
        $node->setAttribute('xmlns', $namespace);
        $request->getDocument()->appendChild($node);

        return $node;
    }
}
