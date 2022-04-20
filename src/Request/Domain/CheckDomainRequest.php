<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Common\CheckNode;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Domain\DomainCheckNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\CheckDomainResponse;

/**
 * Object representation of the request of domain checking command.
 */
class CheckDomainRequest extends AbstractRequest
{
    private array $domains = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        if (count($this->domains) === 0) {
            throw new UnexpectedValueException('List of domains to be checking cannot be empty.');
        }

        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $checkNode = CheckNode::create($this, $commandNode);
        $domainCheckNode = DomainCheckNode::create($this, $checkNode);
        foreach ($this->domains as $item) {
            DomainNameNode::create($this, $domainCheckNode, $item);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return CheckDomainResponse::class;
    }

    /**
     * Adding a domain to the list.
     *
     * @param string $domain fully qualified name of the domain object
     */
    public function addDomain(string $domain): self
    {
        $this->domains[$domain] = $domain;

        return $this;
    }

    /**
     * Removing a domain from the list.
     *
     * @param string $domain fully qualified name of the domain object
     */
    public function removeDomain(string $domain): self
    {
        unset($this->domains[$domain]);

        return $this;
    }
}
