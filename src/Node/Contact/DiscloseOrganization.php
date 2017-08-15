<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <contact:org> node for disclosing information.
 */
class DiscloseOrganization extends AbstractNode
{
    const TYPE_LOC = 'loc';
    const TYPE_INT = 'int';

    /**
     * @param RequestInterface $request    The request object to which the node belongs
     * @param array            $parameters Array of parameters who will be passed in self::handleParameters
     */
    public function __construct(RequestInterface $request, $parameters = [])
    {
        parent::__construct($request, 'contact:org', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        if (!isset($parameters['type'])) {
            throw new InvalidArgumentException('Missing parameter with a key \'type\'.');
        }
        if (!in_array($parameters['type'], [self::TYPE_INT, self::TYPE_LOC])) {
            throw new UnexpectedValueException(sprintf(
                'The value of the parameter with a key \'type\' must be set to \'%s\' or \'%s\'.',
                self::TYPE_INT,
                self::TYPE_LOC
            ));
        }

        $this->getNode()->setAttribute('type', $parameters['type']);
    }
}
