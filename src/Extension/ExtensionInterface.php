<?php

namespace Struzik\EPPClient\Extension;

use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\Response\ResponseInterface;

/**
 * Describes the extension object.
 */
interface ExtensionInterface
{
    /**
     * Setting up the namespaces used in the extension.
     *
     * @param EPPClient $client instance of EPPClient
     */
    public function setupNamespaces(EPPClient $client);

    /**
     * Handle response in the extension. Used for setting add-ons in the response object.
     *
     * @param ResponseInterface $response response object
     */
    public function handleResponse(ResponseInterface $response);
}
