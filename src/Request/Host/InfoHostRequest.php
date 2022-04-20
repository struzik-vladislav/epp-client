<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\InfoNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Host\HostInfoNode;
use Struzik\EPPClient\Node\Host\HostNameNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\InfoHostResponse;

/**
 * Object representation of the request of host information command.
 */
class InfoHostRequest extends AbstractRequest
{
    private string $host = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $infoNode = InfoNode::create($this, $commandNode);
        $hostInfoNode = HostInfoNode::create($this, $infoNode);
        HostNameNode::create($this, $hostInfoNode, $this->host);
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return InfoHostResponse::class;
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
}
