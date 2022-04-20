<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactDiscloseNode
{
    public const FLAG_HIDE = '0';
    public const ALT_FLAG_HIDE = 'false';
    public const FLAG_SHOW = '1';
    public const ALT_FLAG_SHOW = 'true';

    public static function create(RequestInterface $request, \DOMElement $parentNode, string $flag): \DOMElement
    {
        if ($flag === '') {
            throw new InvalidArgumentException('Invalid parameter "flag".');
        }
        if (!in_array($flag, [self::FLAG_HIDE, self::ALT_FLAG_HIDE, self::FLAG_SHOW, self::ALT_FLAG_SHOW], true)) {
            throw new UnexpectedValueException(sprintf('The value of the parameter with a key "flag" must be set to "%s", "%s", "%s" or "%s".', self::FLAG_HIDE, self::ALT_FLAG_HIDE, self::FLAG_SHOW, self::ALT_FLAG_SHOW));
        }

        $node = $request->getDocument()->createElement('contact:disclose');
        $node->setAttribute('flag', $flag);
        $parentNode->appendChild($node);

        return $node;
    }
}
