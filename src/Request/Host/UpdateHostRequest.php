<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Common\UpdateNode;
use Struzik\EPPClient\Node\Host\HostAddNode;
use Struzik\EPPClient\Node\Host\HostAddressNode;
use Struzik\EPPClient\Node\Host\HostChangeNode;
use Struzik\EPPClient\Node\Host\HostNameNode;
use Struzik\EPPClient\Node\Host\HostRemoveNode;
use Struzik\EPPClient\Node\Host\HostStatusNode;
use Struzik\EPPClient\Node\Host\HostUpdateNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\UpdateHostResponse;

/**
 * Object representation of the request of host updating command.
 */
class UpdateHostRequest extends AbstractRequest
{
    private string $host = '';
    private string $newHost = '';
    private array $statusesForAdding = [];
    private array $statusesForRemoving = [];
    private array $addressesForAdding = [];
    private array $addressesForRemoving = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $updateNode = UpdateNode::create($this, $commandNode);
        $hostUpdateNode = HostUpdateNode::create($this, $updateNode);
        HostNameNode::create($this, $hostUpdateNode, $this->host);
        if (count($this->addressesForAdding) > 0 || count($this->statusesForAdding) > 0) {
            $hostAddNode = HostAddNode::create($this, $hostUpdateNode);
            foreach ($this->addressesForAdding as $item) {
                HostAddressNode::create($this, $hostAddNode, $item);
            }
            foreach ($this->statusesForAdding as $item) {
                HostStatusNode::create($this, $hostAddNode, $item);
            }
        }
        if (count($this->addressesForRemoving) > 0 || count($this->statusesForRemoving) > 0) {
            $hostRemoveNode = HostRemoveNode::create($this, $hostUpdateNode);
            foreach ($this->addressesForRemoving as $item) {
                HostAddressNode::create($this, $hostRemoveNode, $item);
            }
            foreach ($this->statusesForRemoving as $item) {
                HostStatusNode::create($this, $hostRemoveNode, $item);
            }
        }
        if ($this->newHost !== '') {
            $hostChangeNode = HostChangeNode::create($this, $hostUpdateNode);
            HostNameNode::create($this, $hostChangeNode, $this->newHost);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return UpdateHostResponse::class;
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
     * Setting the new name of the host. OPTIONAL.
     *
     * @param string $newHost fully qualified name of the host object
     */
    public function setNewHost(string $newHost): self
    {
        $this->newHost = $newHost;

        return $this;
    }

    /**
     * Getting the new name of the host.
     */
    public function getNewHost(): string
    {
        return $this->newHost;
    }

    /**
     * Setting the statuses for adding. OPTIONAL.
     *
     * @param array $statuses array of hosts statuses
     */
    public function setStatusesForAdding(array $statuses): self
    {
        $this->statusesForAdding = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for adding.
     */
    public function getStatusesForAdding(): array
    {
        return $this->statusesForAdding;
    }

    /**
     * Setting the statuses for removing. OPTIONAL.
     *
     * @param array $statuses array of hosts statuses
     */
    public function setStatusesForRemoving(array $statuses): self
    {
        $this->statusesForRemoving = $statuses;

        return $this;
    }

    /**
     * Getting the statuses for removing.
     */
    public function getStatusesForRemoving(): array
    {
        return $this->statusesForRemoving;
    }

    /**
     * Setting the IP addresses for adding. OPTIONAL.
     *
     * @param array $addresses array of IP addresses
     */
    public function setAddressesForAdding(array $addresses): self
    {
        $this->addressesForAdding = $addresses;

        return $this;
    }

    /**
     * Getting the IP addresses for adding.
     */
    public function getAddressesForAdding(): array
    {
        return $this->addressesForAdding;
    }

    /**
     * Setting the IP addresses for removing. OPTIONAL.
     *
     * @param array $addresses array of IP addresses
     */
    public function setAddressesForRemoving(array $addresses): self
    {
        $this->addressesForRemoving = $addresses;

        return $this;
    }

    /**
     * Getting the IP addresses for removing.
     */
    public function getAddressesForRemoving(): array
    {
        return $this->addressesForRemoving;
    }
}
