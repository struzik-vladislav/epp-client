<?php

namespace Struzik\EPPClient\Response\Contact\Helper;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\ResponseInterface;

/**
 * Helper for object representation of a <contact:postalInfo> node.
 */
class PostalInfo
{
    private ResponseInterface $response;
    private \DOMNode $node;

    public function __construct(ResponseInterface $response, \DOMNode $node)
    {
        if ($node->nodeName !== 'contact:postalInfo') {
            throw new UnexpectedValueException(sprintf('The name of the passed node must be "contact:postalInfo", "%s" given.', $node->nodeName));
        }

        $this->response = $response;
        $this->node = $node;
    }

    /**
     * The name of the individual or role represented by the contact.
     */
    public function getName(): string
    {
        return $this->response->getFirst('contact:name', $this->node)->nodeValue;
    }

    /**
     * The name of the organization.
     */
    public function getOrganization(): ?string
    {
        $node = $this->response->getFirst('contact:org', $this->node);
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The contact's street address.
     */
    public function getStreet(): array
    {
        $nodes = $this->response->get('contact:addr/contact:street', $this->node);

        return array_map(static fn (\DOMNode $node): string => $node->nodeValue, iterator_to_array($nodes));
    }

    /**
     * The contact's city.
     */
    public function getCity(): string
    {
        return $this->response->getFirst('contact:addr/contact:city', $this->node)->nodeValue;
    }

    /**
     * The contact's state or province.
     */
    public function getState(): ?string
    {
        $node = $this->response->getFirst('contact:addr/contact:sp', $this->node);
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The contact's postal code.
     */
    public function getPostalCode(): ?string
    {
        $node = $this->response->getFirst('contact:addr/contact:pc', $this->node);
        if ($node === null) {
            return null;
        }

        return $node->nodeValue;
    }

    /**
     * The contact's country code.
     */
    public function getCountryCode(): string
    {
        return $this->response->getFirst('contact:addr/contact:cc', $this->node)->nodeValue;
    }
}
