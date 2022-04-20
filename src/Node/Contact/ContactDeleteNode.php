<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Request\RequestInterface;

class ContactDeleteNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $namespace = $request->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_CONTACT);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the contact namespace cannot be empty.');
        }

        $node = $request->getDocument()->createElement('contact:delete');
        $node->setAttribute('xmlns:contact', $namespace);
        $parentNode->appendChild($node);

        return $node;
    }
}
