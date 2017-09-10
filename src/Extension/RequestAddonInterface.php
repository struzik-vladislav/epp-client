<?php

namespace Struzik\EPPClient\Extension;

use Struzik\EPPClient\Request\RequestInterface;

/**
 * Describes the add-ons for request object.
 */
interface RequestAddonInterface
{
    /**
     * Handling the parameters and building add-on.
     */
    public function build(RequestInterface $request);

    /**
     * Getting the root node of the add-on.
     *
     * @return \DOMElement
     */
    public function getRoot();
}
