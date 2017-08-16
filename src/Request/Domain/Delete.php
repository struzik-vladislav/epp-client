<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\Delete as DeleteResponse;
use Struzik\EPPClient\Node\Common\Delete as DeleteNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Domain\Name;
use Struzik\EPPClient\Node\Domain\Delete as DomainDeleteNode;

/**
 * Object representation of the request of domain deleting command.
 */
class Delete extends AbstractRequest
{
    /**
     * @var string
     */
    private $domain;

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

        $domainDelete = new DomainDeleteNode($this);
        $delete->append($domainDelete);

        $domainName = new Name($this, ['domain' => $this->domain]);
        $domainDelete->append($domainName);

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
     * Getting the name of the domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Setting the name of the domain.
     *
     * @param string $domain fully qualified name of the domain object
     *
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }
}
