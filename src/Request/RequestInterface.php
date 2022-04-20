<?php

namespace Struzik\EPPClient\Request;

use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\Extension\RequestAddonInterface;

/**
 * Describes the request object.
 */
interface RequestInterface
{
    /**
     * Instance of EPP client.
     */
    public function getClient(): EPPClient;

    /**
     * Instance of XML-document of request.
     */
    public function getDocument(): \DOMDocument;

    /**
     * Handling the request object parameters and building XML document.
     */
    public function build(): void;

    /**
     * Getting the name of the response object class.
     */
    public function getResponseClass(): string;

    /**
     * Adding add-ons in the response object.
     */
    public function addExtAddon(RequestAddonInterface $addon): void;

    /**
     * Removing add-on by class name.
     */
    public function removeExtAddon(string $className): void;

    /**
     * Find add-on by class name.
     */
    public function findExtAddon(string $className): ?RequestAddonInterface;
}
