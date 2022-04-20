<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class HostAddressNode
{
    public const IP_V4 = 'v4';
    public const IP_V6 = 'v6';

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $address): \DOMElement
    {
        if ($address === '') {
            throw new InvalidArgumentException('Invalid parameter "address".');
        }

        if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $type = self::IP_V4;
        } elseif (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $type = self::IP_V6;
        } else {
            throw new UnexpectedValueException('The value of the parameter "address" must be a valid IPv4 or IPv6 address.');
        }

        $node = $request->getDocument()->createElement('host:addr', $address);
        $node->setAttribute('ip', $type);
        $parentNode->appendChild($node);

        return $node;
    }
}
