<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\Check as CheckResponse;
use Struzik\EPPClient\Node\Common\Check as CheckNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Domain\Name;
use Struzik\EPPClient\Node\Domain\Check as DomainCheckNode;
use Struzik\EPPClient\Exception\UnexpectedValueException;

/**
 * Object representation of the request of domain checking command.
 */
class Check extends AbstractRequest
{
    /**
     * @var array
     */
    private $domains = [];

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

        $domainCheck = new DomainCheckNode($this);
        $check->append($domainCheck);

        if (count($this->domains) === 0) {
            throw new UnexpectedValueException('List of domains to be checking cannot be empty.');
        }

        foreach ($this->domains as $item) {
            $domainName = new Name($this, ['domain' => $item]);
            $domainCheck->append($domainName);
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
     * Adding a domain to the list.
     *
     * @param string $domain fully qualified name of the domain object
     *
     * @return self
     */
    public function addDomain($domain)
    {
        if (!isset($this->domains[$domain])) {
            $this->domains[$domain] = $domain;
        }

        return $this;
    }

    /**
     * Removing a domain from the list.
     *
     * @param string $domain fully qualified name of the domain object
     *
     * @return self
     */
    public function removeDomain($domain)
    {
        if (isset($this->domains[$domain])) {
            unset($this->domains[$domain]);
        }

        return $this;
    }
}
