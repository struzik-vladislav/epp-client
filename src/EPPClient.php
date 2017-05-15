<?php

namespace Struzik\EPPClient;

use Struzik\EPPClient\Connection\ConnectionInterface;
use Psr\Log\LoggerInterface;

class EPPClient
{
    /**
     * Connection to EPP server.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Logger object.
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ConnectionInterface $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function connect()
    {
        if ($this->connection->isOpened()) {
            return true;
        }

        $this->connection->open();
    }
}
