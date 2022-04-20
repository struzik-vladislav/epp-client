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
     *
     * @param RequestInterface $request instance of request object
     */
    public function build(RequestInterface $request): void;
}
