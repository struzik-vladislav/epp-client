<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\Info as InfoResponse;
use Struzik\EPPClient\Node\Common\Info as InfoNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Host\Info as HostInfoNode;
use Struzik\EPPClient\Node\Host\Name;

/**
 * Object representation of the request of host information command.
 */
class Info extends AbstractRequest
{
    /**
     * @var string
     */
    private $host;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $info = new InfoNode($this);
        $command->append($info);

        $hostInfo = new HostInfoNode($this);
        $info->append($hostInfo);

        $hostName = new Name($this, ['host' => $this->host]);
        $hostInfo->append($hostName);

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return InfoResponse::class;
    }

    /**
     * Setting the name of the host. REQUIRED.
     *
     * @param string $host fully qualified name of the host object
     *
     * @return self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Getting the name of the host.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
}
