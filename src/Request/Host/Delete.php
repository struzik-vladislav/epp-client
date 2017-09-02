<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\Delete as DeleteResponse;
use Struzik\EPPClient\Node\Common\Delete as DeleteNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Host\Name;
use Struzik\EPPClient\Node\Host\Delete as HostDeleteNode;

/**
 * Object representation of the request of host deleting command.
 */
class Delete extends AbstractRequest
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

        $delete = new DeleteNode($this);
        $command->append($delete);

        $hostDelete = new HostDeleteNode($this);
        $delete->append($hostDelete);

        $hostName = new Name($this, ['host' => $this->host]);
        $hostDelete->append($hostName);

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return DeleteResponse::class;
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
