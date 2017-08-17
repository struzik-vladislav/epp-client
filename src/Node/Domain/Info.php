<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Node\AbstractNode;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Request\RequestInterface;

/**
 * Object representation of the <domain:info> node.
 */
class Info extends AbstractNode
{
    /**
     * @param RequestInterface $request The request object to which the node belongs
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request, 'domain:info');
    }

    /**
     * {@inheritdoc}
     */
    protected function handleParameters($parameters = [])
    {
        $namespace = $this->getRequest()
            ->getClient()
            ->getNamespaceCollection()
            ->offsetGet(NamespaceCollection::NS_NAME_DOMAIN);
        if (!$namespace) {
            throw new UnexpectedValueException('URI of the domain namespace cannot be empty.');
        }

        $this->getNode()->setAttribute('xmlns:domain', $namespace);
    }
}
