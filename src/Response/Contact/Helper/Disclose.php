<?php

namespace Struzik\EPPClient\Response\Contact\Helper;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\ResponseInterface;

/**
 * Helper for object representation of a <contact:disclose> node.
 */
class Disclose
{
    private ResponseInterface $response;
    private \DOMNode $node;

    public function __construct(ResponseInterface $response, \DOMNode $node)
    {
        if ($node->nodeName !== 'contact:disclose') {
            throw new UnexpectedValueException(sprintf('The name of the passed node must be "contact:disclose", "%s" given.', $node->nodeName));
        }

        $this->response = $response;
        $this->node = $node;
    }

    /**
     * The value of the "flag" attribute.
     */
    public function getFlag(): string
    {
        return $this->node->getAttribute('flag');
    }

    /**
     * Existence of the <contact:name type="int"/> node.
     */
    public function nameIntExists(): bool
    {
        return (bool) $this->response->getFirst('contact:name[@type=\'int\']', $this->node);
    }

    /**
     * Existence of the <contact:name type="loc"/> node.
     */
    public function nameLocExists(): bool
    {
        return (bool) $this->response->getFirst('contact:name[@type=\'loc\']', $this->node);
    }

    /**
     * Existence of the <contact:org type="int"/> node.
     */
    public function organizationIntExists(): bool
    {
        return (bool) $this->response->getFirst('contact:org[@type=\'int\']', $this->node);
    }

    /**
     * Existence of the <contact:org type="loc"/> node.
     */
    public function organizationLocExists(): bool
    {
        return (bool) $this->response->getFirst('contact:org[@type=\'loc\']', $this->node);
    }

    /**
     * Existence of the <contact:addr type="int"/> node.
     */
    public function addressIntExists(): bool
    {
        return (bool) $this->response->getFirst('contact:addr[@type=\'int\']', $this->node);
    }

    /**
     * Existence of the <contact:addr type="loc"/> node.
     */
    public function addressLocExists(): bool
    {
        return (bool) $this->response->getFirst('contact:addr[@type=\'loc\']', $this->node);
    }

    /**
     * Existence of the <contact:voice/> node.
     */
    public function voiceExists(): bool
    {
        return (bool) $this->response->getFirst('contact:voice', $this->node);
    }

    /**
     * Existence of the <contact:fax/> node.
     */
    public function faxExists(): bool
    {
        return (bool) $this->response->getFirst('contact:fax', $this->node);
    }

    /**
     * Existence of the <contact:email/> node.
     */
    public function emailExists(): bool
    {
        return (bool) $this->response->getFirst('contact:email', $this->node);
    }
}
