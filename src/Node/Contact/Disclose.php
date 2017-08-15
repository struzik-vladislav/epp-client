<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <contact:disclose> node.
 */
class Disclose extends AbstractNode
{
    const FLAG_HIDE = '0';
    const ALT_FLAG_HIDE = 'false';
    const FLAG_SHOW = '1';
    const ALT_FLAG_SHOW = 'true';

    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'contact:disclose', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['flag'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'flag\'.');
        }
        if (!in_array($parameters['flag'], [self::FLAG_HIDE, self::ALT_FLAG_HIDE, self::FLAG_SHOW, self::ALT_FLAG_SHOW])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter with a key \'flag\' must be set to \'%s\', \'%s\', \'%s\' or \'%s\'.',
                self::FLAG_HIDE,
                self::ALT_FLAG_HIDE,
                self::FLAG_SHOW,
                self::ALT_FLAG_SHOW
            ));
        }

        $this->getNode()->setAttribute('flag', $parameters['flag']);
    }
}
