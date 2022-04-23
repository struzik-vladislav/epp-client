<?php

namespace Struzik\EPPClient\Node\Common;

use Struzik\EPPClient\Exception\RuntimeException;
use Struzik\EPPClient\Request\RequestInterface;

class ExtensionNode
{
    public static function create(RequestInterface $request): \DOMElement
    {
        $extensionNodeList = $request->getDocument()->getElementsByTagName('extension');
        $extensionNode = $extensionNodeList->count() > 0 ? $extensionNodeList->item(0) : null;
        if ($extensionNode instanceof \DOMElement) {
            return $extensionNode;
        }

        $commandNodeList = $request->getDocument()->getElementsByTagName('command');
        $commandNode = $commandNodeList->count() > 0 ? $commandNodeList->item(0) : null;
        if (!$commandNode instanceof \DOMElement) {
            throw new RuntimeException('Node <command> not found.');
        }

        $transactionNodeList = $request->getDocument()->getElementsByTagName('clTRID');
        $transactionNode = $transactionNodeList->count() > 0 ? $transactionNodeList->item(0) : null;
        if ($transactionNode instanceof \DOMElement) {
            $extensionNode = $request->getDocument()->createElement('extension');
            $commandNode->insertBefore($extensionNode, $transactionNode);

            return $extensionNode;
        }

        $extensionNode = $request->getDocument()->createElement('extension');
        $commandNode->appendChild($extensionNode);

        return $extensionNode;
    }
}
