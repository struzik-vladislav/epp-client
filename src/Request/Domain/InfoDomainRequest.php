<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\InfoNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Domain\DomainAuthInfoNode;
use Struzik\EPPClient\Node\Domain\DomainInfoNode;
use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Node\Domain\DomainPasswordNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\InfoDomainResponse;

/**
 * Object representation of the request of domain information command.
 */
class InfoDomainRequest extends AbstractRequest
{
    private string $domain = '';
    private string $hosts = '';
    private string $password = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $infoNode = InfoNode::create($this, $commandNode);
        $domainInfoNode = DomainInfoNode::create($this, $infoNode);
        DomainNameNode::create($this, $domainInfoNode, $this->domain, $this->hosts);
        if ($this->password !== '') {
            $domainAuthInfoNode = DomainAuthInfoNode::create($this, $domainInfoNode);
            DomainPasswordNode::create($this, $domainAuthInfoNode, $this->password);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return InfoDomainResponse::class;
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
     * Setting the attribute to control return of information describing
     * hosts related to the domain object. OPTIONAL.
     *
     * @param string $hosts attribute value
     */
    public function setHosts(string $hosts = ''): self
    {
        $this->hosts = $hosts;

        return $this;
    }

    /**
     * Getting the attribute to control return of information describing
     * hosts related to the domain object.
     */
    public function getHosts(): string
    {
        return $this->hosts;
    }

    /**
     * Setting the password of the domain. OPTIONAL.
     *
     * @param string $password authorization information associated with the domain object
     */
    public function setPassword(string $password = ''): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the domain.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
