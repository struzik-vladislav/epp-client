<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <epp> node.
 */
class Epp extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'epp');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        $namespace = $this->getRequest()
            ->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_ROOT);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the root namespace cannot be empty.');
        }

        $this->getNode()->setAttribute('xmlns', $namespace);
    }
}
