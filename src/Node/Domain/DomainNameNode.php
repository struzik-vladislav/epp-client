<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainNameNode
{
    public const HOSTS_ALL = 'all';
    public const HOSTS_DEL = 'del';
    public const HOSTS_SUB = 'sub';
    public const HOSTS_NONE = 'none';

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $domain, string $hosts = ''): \DOMElement
    {
        if ($domain === '') {
            throw new InvalidArgumentException('Invalid parameter "domain".');
        }

        $node = $request->getDocument()->createElement('domain:name', $domain);
        $parentNode->appendChild($node);

        if ($hosts !== '') {
            if (!in_array($hosts, [self::HOSTS_ALL, self::HOSTS_DEL, self::HOSTS_SUB, self::HOSTS_NONE], true)) {
                throw new UnexpectedValueException(sprintf('The value of the parameter "hosts" must be set to "%s", "%s", "%s" or "%s".', self::HOSTS_ALL, self::HOSTS_DEL, self::HOSTS_SUB, self::HOSTS_NONE));
            }
            $node->setAttribute('hosts', $hosts);
        }

        return $node;
    }
}
