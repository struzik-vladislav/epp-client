<?php

namespace Struzik\EPPClient\Tests\Connection;

use Struzik\EPPClient\Connection\ConnectionInterface;

class TestConnection implements ConnectionInterface
{
    public function open(): void
    {
    }

    public function isOpened(): bool
    {
        return true;
    }

    public function close(): void
    {
    }

    public function read(): string
    {
        return '';
    }

    public function write(string $xml): void
    {
    }
}
