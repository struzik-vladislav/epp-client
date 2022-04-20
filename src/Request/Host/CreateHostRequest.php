<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\CreateNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Host\HostAddressNode;
use Struzik\EPPClient\Node\Host\HostCreateNode;
use Struzik\EPPClient\Node\Host\HostNameNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\CreateHostResponse;

/**
 * Object representation of the request of host creating command.
 */
class CreateHostRequest extends AbstractRequest
{
    private string $host = '';
    private array $addresses = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $createNode = CreateNode::create($this, $commandNode);
        $hostCreateNode = HostCreateNode::create($this, $createNode);
        HostNameNode::create($this, $hostCreateNode, $this->host);
        foreach ($this->addresses as $item) {
            HostAddressNode::create($this, $hostCreateNode, $item);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return CreateHostResponse::class;
    }

    /**
     * Setting the name of the host. REQUIRED.
     *
     * @param string $host fully qualified name of the host object
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Getting the name of the host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Setting the addresses of the host object. OPTIONAL.
     *
     * @param array $addresses IP addresses
     */
    public function setAddresses(array $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Getting the addresses of the host object.
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }
}
