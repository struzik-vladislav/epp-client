<?php

namespace Struzik\EPPClient\Connection;

/**
 * The interface object of connection to the EPP server.
 */
interface ConnectionInterface
{
    /**
     * Header lenght according RFC 5734 (See: https://tools.ietf.org/html/rfc5734#section-4).
     */
    const HEADER_LENGTH = 4;

    /**
     * Establishes the connection with the EPP server.
     */
    public function open();

    /**
     * Whether an actual connection to the EPP server is established.
     *
     * @return bool
     */
    public function isOpened();

    /**
     * Closes the connection.
     */
    public function close();

    /**
     * Reading XML data from a connection.
     *
     * @return string
     */
    public function read();

    /**
     * Write XML data to the connection.
     *
     * @param string $xml RAW-request
     */
    public function write($xml);
}
