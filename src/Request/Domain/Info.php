<?php

namespace Struzik\EPPClient\Request\Domain;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Domain\Info as InfoResponse;
use Struzik\EPPClient\Node\Common\Info as InfoNode;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Domain\Info as DomainInfoNode;
use Struzik\EPPClient\Node\Domain\Name;
use Struzik\EPPClient\Node\Domain\Password;
use Struzik\EPPClient\Node\Domain\AuthorizationInfo;

/**
 * Object representation of the request of domain information command.
 */
class Info extends AbstractRequest
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $hosts;

    /**
     * @var string
     */
    private $password;

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

        $domainInfo = new DomainInfoNode($this);
        $info->append($domainInfo);

        $nameParameters = ['domain' => $this->domain];
        if ($this->hosts !== null) {
            $nameParameters['hosts'] = $this->hosts;
        }
        $domainName = new Name($this, $nameParameters);
        $domainInfo->append($domainName);

        if ($this->password !== null) {
            $domainAuthorization = new AuthorizationInfo($this);
            $domainInfo->append($domainAuthorization);

            $domainPassword = new Password($this, ['password' => $this->password]);
            $domainAuthorization->append($domainPassword);
        }

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
     * Setting the attribute to control return of information describing hosts related to the domain object.
     *
     * @param string|null $hosts attribute value
     *
     * @return self
     */
    public function setHosts($hosts = null)
    {
        $this->hosts = $hosts;

        return $this;
    }

    /**
     * Getting the attribute to control return of information describing hosts related to the domain object.
     *
     * @return string|null
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    /**
     * Setting the password of the domain.
     *
     * @param string|null $password authorization information associated with the domain object
     *
     * @return self
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the password of the domain.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }
}
