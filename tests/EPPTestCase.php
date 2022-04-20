<?php

namespace Struzik\EPPClient\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Struzik\EPPClient\Connection\ConnectionInterface;
use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Tests\Connection\TestConnection;
use Struzik\EPPClient\Tests\IdGenerator\TestGenerator;

class EPPTestCase extends TestCase
{
    public ConnectionInterface $eppConnection;
    public EPPClient $eppClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eppConnection = new TestConnection();
        $this->eppClient = new EPPClient($this->eppConnection, new NullLogger());
        $this->eppClient->setIdGenerator(new TestGenerator());
        $namespaceCollection = $this->eppClient->getNamespaceCollection();
        $namespaceCollection->offsetSet(NamespaceCollection::NS_NAME_ROOT, 'urn:ietf:params:xml:ns:epp-1.0');
        $namespaceCollection->offsetSet(NamespaceCollection::NS_NAME_CONTACT, 'urn:ietf:params:xml:ns:contact-1.0');
        $namespaceCollection->offsetSet(NamespaceCollection::NS_NAME_HOST, 'urn:ietf:params:xml:ns:host-1.0');
        $namespaceCollection->offsetSet(NamespaceCollection::NS_NAME_DOMAIN, 'urn:ietf:params:xml:ns:domain-1.0');
    }
}
