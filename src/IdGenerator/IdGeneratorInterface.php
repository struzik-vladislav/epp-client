<?php

namespace Struzik\EPPClient\IdGenerator;

use Struzik\EPPClient\Request\RequestInterface;

/**
 * Describes the identifier generator object.
 */
interface IdGeneratorInterface
{
    /**
     * Getting pseudo unique identifier for request.
     *
     * @param RequestInterface $request Request object
     */
    public function generate(RequestInterface $request): string;
}
