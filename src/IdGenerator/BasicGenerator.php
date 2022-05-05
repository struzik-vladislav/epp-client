<?php

namespace Struzik\EPPClient\IdGenerator;

use Struzik\EPPClient\Request\RequestInterface;

/**
 * Basic implementation of identifier generator. Based on DateTime.
 */
class BasicGenerator implements IdGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(RequestInterface $request): string
    {
        $class = (new \ReflectionClass($request))->getShortName();
        $prefix = substr($class, -7) === 'Request'
            ? substr($class, 0, -7)
            : $class;
        $prefix = preg_replace('/([A-Z])/u', '-$1', $prefix);
        $prefix = trim($prefix, '-');

        $identifierPieces[] = $prefix;
        $identifierPieces[] = (new \DateTime())->format('YmdHis.u');
        $identifier = implode('-', $identifierPieces);
        $identifier = mb_strtoupper($identifier);

        return $identifier;
    }
}
