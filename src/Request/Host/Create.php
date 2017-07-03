<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\Create as CreateResponse;
use Struzik\EPPClient\Node\Common\Create as CreateNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Host\Name;
use Struzik\EPPClient\Node\Host\Create as HostCreateNode;
use Struzik\EPPClient\Node\Host\Address;

/**
 * Object representation of the request of host creating command.
 */
class Create extends AbstractRequest
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $addresses = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $create = new CreateNode($this);
        $command->append($create);

        $hostCreate = new HostCreateNode($this);
        $create->append($hostCreate);

        $hostName = new Name($this, ['host' => $this->host]);
        $hostCreate->append($hostName);

        foreach ($this->addresses as $item) {
            $address = new Address($this, ['address' => $item]);
            $hostCreate->append($address);
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return CreateResponse::class;
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

    /**
     * Setting the name of the host.
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
     * Adding an IP to the address list.
     *
     * @param string $address valid IP address
     *
     * @return self
     */
    public function addIP($address)
    {
        if (!isset($this->addresses[$address])) {
            $this->addresses[$address] = $address;
        }

        return $this;
    }

    /**
     * Removing an IP from the address list.
     *
     * @param string $address valid IP address
     *
     * @return self
     */
    public function removeIP($address)
    {
        if (isset($this->addresses[$address])) {
            unset($this->addresses[$address]);
        }

        return $this;
    }
}
