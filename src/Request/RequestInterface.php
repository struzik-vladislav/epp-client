<?php

namespace Struzik\EPPClient\Request;

/**
 * Describes the request object.
 */
interface RequestInterface
{
    /**
     * Specifies whether the request was built.
     *
     * @return bool
     */
    public function isBuilt();

    /**
     * Handling the request object parameters and building XML document.
     */
    public function build();

    /**
     * Getting XML representation of the request.
     *
     * @return string
     */
    public function saveXML();

    /**
     * Create new element node.
     *
     * @param string $name  The tag name of the element
     * @param string $value The value of the element
     *
     * @return DOMElement|false
     */
    public function createElement($name, $value = null);

    /**
     * Getting the name of the response object class.
     *
     * @return string
     */
    public function getResponseClass();
}
