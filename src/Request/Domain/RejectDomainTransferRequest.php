<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Common\TransferNode;
use Struzik\EPPClient\Node\Domain\DomainAuthInfoNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Node\Domain\DomainPasswordNode;
use Struzik\EPPClient\Node\Domain\DomainTransferNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\TransferDomainResponse;

/**
 * Object representation of the request of the domain transferring with operation 'reject'.
 */
class RejectDomainTransferRequest extends AbstractRequest
{
    private string $domain = '';
    private string $password = '';
    private string $passwordROIdentifier = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $transferNode = TransferNode::create($this, $commandNode, TransferNode::OPERATION_REJECT);
        $domainTransferNode = DomainTransferNode::create($this, $transferNode);
        DomainNameNode::create($this, $domainTransferNode, $this->domain);
        $domainAuthInfoNode = DomainAuthInfoNode::create($this, $domainTransferNode);
        DomainPasswordNode::create($this, $domainAuthInfoNode, $this->password, $this->passwordROIdentifier);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return TransferDomainResponse::class;
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

    /**
     * Setting the password. REQUIRED.
     *
     * @param string $password associated authorization information
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setting registry object identifier associated with the password. OPTIONAL.
     *
     * @param string $passwordROIdentifier object identifier
     */
    public function setPasswordROIdentifier(string $passwordROIdentifier): self
    {
        $this->passwordROIdentifier = $passwordROIdentifier;

        return $this;
    }

    /**
     * Getting registry object identifier associated with the password.
     */
    public function getPasswordROIdentifier(): string
    {
        return $this->passwordROIdentifier;
    }
}
