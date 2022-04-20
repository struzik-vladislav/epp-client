<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Request\RequestInterface;

class ContactCheckNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $namespace = $request->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_CONTACT);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the contact namespace cannot be empty.');
        }

        $node = $request->getDocument()->createElement('contact:check');
        $node->setAttribute('xmlns:contact', $namespace);
        $parentNode->appendChild($node);

        return $node;
    }
}
