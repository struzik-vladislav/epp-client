<?php

namespace Struzik\EPPClient\Extension;

use Struzik\EPPClient\Exception\RuntimeException;
use Struzik\EPPClient\Request\RequestInterface;

class TaggedRequestAddon implements RequestAddonInterface
{
    public string $customTag = '';

    public function build(RequestInterface $request): void
    {
        $transactionNodeList = $request->getDocument()->getElementsByTagName('clTRID');
        $transactionNode = $transactionNodeList->count() > 0 ? $transactionNodeList->item(0) : null;
        if (!$transactionNode instanceof \DOMElement) {
            throw new RuntimeException('Node <clTRID> not found.');
        }

        if ($this->customTag) {
            $transactionNode->nodeValue .= '/'.$this->customTag;
        }
    }

    public function getCustomTag(): string
    {
        return $this->customTag;
    }

    public function setCustomTag(string $customTag): self
    {
        $this->customTag = $customTag;

        return $this;
    }
}
