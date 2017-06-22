<?php

namespace Struzik\EPPClient\IdGenerator;

use Struzik\EPPClient\Request\RequestInterface;

/**
 * Basic implementation of identifier generator.
 * Based on uniqid() function.
 */
class BasicGenerator implements IdGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(RequestInterface $request)
    {
        $class = get_class($request);
        $pices = explode('\\', $class);
        $prefixPices = count($pices) > 2 ? array_slice($pices, -2) : $pices;
        $prefixPices[] = date('YmdHis');
        $prefixPices[] = uniqid();
        $identifier = implode('-', $prefixPices);
        $identifier = mb_strtoupper($identifier);

        return $identifier;
    }
}
