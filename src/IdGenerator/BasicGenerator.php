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
        $pieces = explode('\\', $class);
        $prefixPieces = count($pieces) > 2 ? array_slice($pieces, -2) : $pieces;
        $prefixPieces[] = date('YmdHis');
        $prefixPieces[] = uniqid();
        $identifier = implode('-', $prefixPieces);
        $identifier = mb_strtoupper($identifier);

        return $identifier;
    }
}
