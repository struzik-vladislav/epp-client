<?php

namespace Struzik\EPPClient\Request\Host;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Host\Update as UpdateResponse;
use Struzik\EPPClient\Node\Common\Update as UpdateNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Host\Add;
use Struzik\EPPClient\Node\Host\Name;
use Struzik\EPPClient\Node\Host\Update as HostUpdateNode;
use Struzik\EPPClient\Node\Host\Remove;
use Struzik\EPPClient\Node\Host\Status;
use Struzik\EPPClient\Node\Host\Change;
use Struzik\EPPClient\Node\Host\Address;

/**
 * Object representation of the request of host updating command.
 */
class Update extends AbstractRequest
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $newHost;

    /**
     * @var array
     */
    private $statusesForAdding = [];

    /**
     * @var array
     */
    private $statusesForRemoving = [];

    /**
     * @var array
     */
    private $addressesForAdding = [];

    /**
     * @var array
     */
    private $addressesForRemoving = [];

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $update = new UpdateNode($this);
        $command->append($update);

        $hostUpdate = new HostUpdateNode($this);
        $update->append($hostUpdate);

        $hostName = new Name($this, ['host' => $this->host]);
        $hostUpdate->append($hostName);

        if (count($this->addressesForAdding) > 0 || count($this->statusesForAdding) > 0) {
            $hostAdd = new Add($this);
            $hostUpdate->append($hostAdd);

            foreach ($this->addressesForAdding as $item) {
                $address = new Address($this, ['address' => $item]);
                $hostAdd->append($address);
            }

            foreach ($this->statusesForAdding as $item) {
                $status = new Status($this, ['status' => $item]);
                $hostAdd->append($status);
            }
        }

        if (count($this->addressesForRemoving) > 0 || count($this->statusesForRemoving) > 0) {
            $hostRemove = new Remove($this);
            $hostUpdate->append($hostRemove);

            foreach ($this->addressesForRemoving as $item) {
                $address = new Address($this, ['address' => $item]);
                $hostRemove->append($address);
            }

            foreach ($this->statusesForRemoving as $item) {
                $status = new Status($this, $item);
                $hostRemove->append($status);
            }
        }

        if ($this->newHost) {
            $hostChange = new Change($this);
            $hostUpdate->append($hostChange);

            $newHostName = new Name($this, ['host' => $this->newHost]);
            $hostChange->append($newHostName);
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return UpdateResponse::class;
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
     * Getting the new name of the host.
     *
     * @return string
     */
    public function getNewHost()
    {
        return $this->newHost;
    }

    /**
     * Setting the new name of the host.
     *
     * @param string $newHost fully qualified name of the host object
     *
     * @return self
     */
    public function setNewHost($newHost)
    {
        $this->newHost = $newHost;

        return $this;
    }

    /**
     * Adding status to the list for adding.
     *
     * @param string $status valid host status
     *
     * @return self
     */
    public function addStatusForAdding($status)
    {
        if (!isset($this->statusesForAdding[$status])) {
            $this->statusesForAdding[$status] = $status;
        }

        return $this;
    }

    /**
     * Removing status from the list for adding.
     *
     * @param string $status valid host status
     *
     * @return self
     */
    public function removeStatusForAdding($status)
    {
        if (isset($this->statusesForAdding[$status])) {
            unset($this->statusesForAdding[$status]);
        }

        return $this;
    }

    /**
     * Adding status to the list for removing.
     *
     * @param string $status valid host status
     *
     * @return self
     */
    public function addStatusForRemoving($status)
    {
        if (!isset($this->statusesForRemoving[$status])) {
            $this->statusesForRemoving[$status] = $status;
        }

        return $this;
    }

    /**
     * Removing status from the list for removing.
     *
     * @param string $status valid host status
     *
     * @return self
     */
    public function removeStatusForRemoving($status)
    {
        if (isset($this->statusesForRemoving[$status])) {
            unset($this->statusesForRemoving[$status]);
        }

        return $this;
    }

    /**
     * Adding an IP to the list for adding.
     *
     * @param string $address valid IP address
     *
     * @return self
     */
    public function addAddressForAdding($address)
    {
        if (!isset($this->addressesForAdding[$address])) {
            $this->addressesForAdding[$address] = $address;
        }

        return $this;
    }

    /**
     * Removing an IP from the list for adding.
     *
     * @param string $address valid IP address
     *
     * @return self
     */
    public function removeAddressForAdding($address)
    {
        if (isset($this->addressesForAdding[$address])) {
            unset($this->addressesForAdding[$address]);
        }

        return $this;
    }

    /**
     * Adding an IP to the list for removing.
     *
     * @param string $address valid IP address
     *
     * @return self
     */
    public function addAddressForRemoving($address)
    {
        if (!isset($this->addressesForRemoving[$address])) {
            $this->addressesForRemoving[$address] = $address;
        }

        return $this;
    }

    /**
     * Removing an IP from the list for removing.
     *
     * @param string $address valid IP address
     *
     * @return self
     */
    public function removeAddressForRemoving($address)
    {
        if (isset($this->addressesForRemoving[$address])) {
            unset($this->addressesForRemoving[$address]);
        }

        return $this;
    }
}
