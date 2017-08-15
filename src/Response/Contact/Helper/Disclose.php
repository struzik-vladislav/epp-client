<?php

namespace Struzik\EPPClient\Response\Contact\Helper;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Response\ResponseInterface;

/**
 * Helper for object representation of a <contact:disclose> node.
 */
class Disclose
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
        if ($node->nodeName !== 'contact:disclose') {
            throw new UnexpectedValueException(sprintf('The name of the passed node must be "contact:disclose", "%s" given.', $node->nodeName));
        }

        $this->response = $response;
        $this->node = $node;
    }

    /**
     * The value of the "flag" attribute.
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->node->getAttribute('flag');
    }

    /**
     * Existence of the <contact:name type="int"/> node.
     *
     * @return bool
     */
    public function nameIntExists()
    {
        $node = $this->response->getFirst('contact:name[@type=\'int\']', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:name type="loc"/> node.
     *
     * @return bool
     */
    public function nameLocExists()
    {
        $node = $this->response->getFirst('contact:name[@type=\'loc\']', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:org type="int"/> node.
     *
     * @return bool
     */
    public function organizationIntExists()
    {
        $node = $this->response->getFirst('contact:org[@type=\'int\']', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:org type="loc"/> node.
     *
     * @return bool
     */
    public function organizationLocExists()
    {
        $node = $this->response->getFirst('contact:org[@type=\'loc\']', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:addr type="int"/> node.
     *
     * @return bool
     */
    public function addressIntExists()
    {
        $node = $this->response->getFirst('contact:addr[@type=\'int\']', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:addr type="loc"/> node.
     *
     * @return bool
     */
    public function addressLocExists()
    {
        $node = $this->response->getFirst('contact:addr[@type=\'loc\']', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:voice/> node.
     *
     * @return bool
     */
    public function voiceExists()
    {
        $node = $this->response->getFirst('contact:voice', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:fax/> node.
     *
     * @return bool
     */
    public function faxExists()
    {
        $node = $this->response->getFirst('contact:fax', $this->node);

        return (bool) $node;
    }

    /**
     * Existence of the <contact:email/> node.
     *
     * @return bool
     */
    public function emailExists()
    {
        $node = $this->response->getFirst('contact:email', $this->node);

        return (bool) $node;
    }
}
