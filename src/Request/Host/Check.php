<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\Check as CheckResponse;
use Struzik\EPPClient\Node\Common\Check as CheckNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Host\Name;
use Struzik\EPPClient\Node\Host\Check as HostCheckNode;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the request of host checking command.
 */
class Check extends AbstractRequest
{
    /**
     * @var array
     */
    private $hosts = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $check = new CheckNode($this);
        $command->append($check);

        $hostCheck = new HostCheckNode($this);
        $check->append($hostCheck);

        if (count($this->hosts) == 0) {
            throw new UnexpectedValueException('List of hosts to be checking cannot be empty');
        }

        foreach ($this->hosts as $item) {
            $hostName = new Name($this, ['host' => $item]);
            $hostCheck->append($hostName);
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return CheckResponse::class;
    }

    /**
     * Adding a hostname to the list.
     *
     * @param string $host hostname
     *
     * @return self
     */
    public function addHost($host)
    {
        if (!isset($this->hosts[$host])) {
            $this->hosts[$host] = $host;
        }

        return $this;
    }

    /**
     * Removing a hostname from the list.
     *
     * @param string $host hostname
     *
     * @return self
     */
    public function removeHost($host)
    {
        if (isset($this->hosts[$host])) {
            unset($this->hosts[$host]);
        }

        return $this;
    }
}
