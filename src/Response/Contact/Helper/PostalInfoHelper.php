<?php

namespace Struzik\EPPClient\Response\Contact\Helper;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\ResponseInterface;

/**
 * Helper for object representation of a <contact:postalInfo> node.
 */
class PostalInfoHelper
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var \DOMNode
     */
    private $node;

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
     *
     * @return string
     */
    public function getName()
    {
        $node = $this->response->getFirst('contact:name', $this->node);

        return $node->nodeValue;
    }

    /**
     * The name of the organization.
     *
     * @return string|null
     */
    public function getOrganization()
    {
        $node = $this->response->getFirst('contact:org', $this->node);

        return $node ? $node->nodeValue : null;
    }

    /**
     * The contact's street address.
     *
     * @return \Generator|null
     */
    public function getStreet()
    {
        $nodes = $this->response->get('contact:addr/contact:street', $this->node);

        if ($nodes->length === 0) {
            return null;
        }

        foreach ($nodes as $node) {
            yield $node->nodeValue;
        }
    }

    /**
     * The contact's city.
     *
     * @return string
     */
    public function getCity()
    {
        $node = $this->response->getFirst('contact:addr/contact:city', $this->node);

        return $node->nodeValue;
    }

    /**
     * The contact's state or province.
     *
     * @return string|null
     */
    public function getState()
    {
        $node = $this->response->getFirst('contact:addr/contact:sp', $this->node);

        return $node ? $node->nodeValue : null;
    }

    /**
     * The contact's postal code.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        $node = $this->response->getFirst('contact:addr/contact:pc', $this->node);

        return $node ? $node->nodeValue : null;
    }

    /**
     * The contact's country code.
     *
     * @return string
     */
    public function getCountryCode()
    {
        $node = $this->response->getFirst('contact:addr/contact:cc', $this->node);

        return $node->nodeValue;
    }
}
