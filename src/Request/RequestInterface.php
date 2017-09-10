<?php

namespace Struzik\EPPClient\Request;

use Struzik\EPPClient\Extension\RequestAddonInterface;

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

    /**
     * Adding add-ons in the response object.
     *
     * @param object $addon add-on object
     */
    public function addExtAddon(RequestAddonInterface $addon);

    /**
     * Removing add-on by class name.
     *
     * @param string $classname class name
     */
    public function removeExtAddon($classname);

    /**
     * Find add-on by class name.
     *
     * @param string $classname class name
     *
     * @return object|null
     */
    public function findExtAddon($classname);
}
