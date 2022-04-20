<?php

namespace Struzik\EPPClient\Connection;

use Struzik\EPPClient\Exception\ConnectionException;

/**
 * The interface object of connection to the EPP server.
 */
interface ConnectionInterface
{
    /**
     * Header length according RFC 5734 (See: https://tools.ietf.org/html/rfc5734#section-4).
     */
    public const HEADER_LENGTH = 4;

    /**
     * Establishes the connection with the EPP server.
     *
     * @throws ConnectionException
     */
    public function open(): void;

    /**
     * Whether an actual connection to the EPP server is established.
     */
    public function isOpened(): bool;

    /**
     * Closing the connection.
     *
     * @throws ConnectionException
     */
    public function close(): void;

    /**
     * Reading XML data from a connection.
     *
     * @throws ConnectionException
     */
    public function read(): string;

    /**
     * Write XML data to the connection.
     *
     * @throws ConnectionException
     */
    public function write(string $xml): void;
}
