<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\DeleteNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Domain\DomainDeleteNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\DeleteDomainResponse;

/**
 * Object representation of the request of domain deleting command.
 */
class DeleteDomainRequest extends AbstractRequest
{
    private string $domain = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $deleteNode = DeleteNode::create($this, $commandNode);
        $domainDeleteNode = DomainDeleteNode::create($this, $deleteNode);
        DomainNameNode::create($this, $domainDeleteNode, $this->domain);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return DeleteDomainResponse::class;
    }

    /**
     * Setting the name of the domain. REQUIRED.
     *
     * @param string $domain fully qualified name of the domain object
     */
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Getting the name of the domain.
     */
    public function getDomain(): string
    {
        return $this->domain;
    }
}
