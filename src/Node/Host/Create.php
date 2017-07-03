<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\Request\RequestInterface;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the <host:create> node.
 */
class Create extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'host:create');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        $namespace = $this->getRequest()
            ->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_HOST);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the host namespace cannot be empty.');
        }

        $this->getNode()->setAttribute('xmlns:host', $namespace);
    }
}
