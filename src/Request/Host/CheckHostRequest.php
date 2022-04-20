<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Exception\UnexpectedValueException;
use Struzik\EPPClient\Node\Common\CheckNode;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Host\HostCheckNode;
use Struzik\EPPClient\Node\Host\HostNameNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\CheckHostResponse;

/**
 * Object representation of the request of host checking command.
 */
class CheckHostRequest extends AbstractRequest
{
    private array $hosts = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        if (count($this->hosts) === 0) {
            throw new UnexpectedValueException('List of hosts to be checking cannot be empty.');
        }

        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $checkNode = CheckNode::create($this, $commandNode);
        $hostCheckNode = HostCheckNode::create($this, $checkNode);
        foreach ($this->hosts as $item) {
            HostNameNode::create($this, $hostCheckNode, $item);
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return CheckHostResponse::class;
    }

    /**
     * Adding a host to the list.
     *
     * @param string $host fully qualified name of the host object
     */
    public function addHost(string $host): self
    {
        $this->hosts[$host] = $host;

        return $this;
    }

    /**
     * Removing a host from the list.
     *
     * @param string $host fully qualified name of the host object
     */
    public function removeHost(string $host): self
    {
        unset($this->hosts[$host]);

        return $this;
    }
}
