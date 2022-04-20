<?php

namespace Struzik\EPPClient\Request\Domain\Helper;

/**
 * Parameters aggregation for domain status structure.
 */
class Status
{
    private string $status = '';
    private string $language = '';
    private string $reason = '';

    /**
     * Setting the status. REQUIRED.
     *
     * @param string $status the value of the status constant
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Getting the status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Setting the language of the reason. OPTIONAL.
     *
     * @param string|null $language ISO code of the language
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Getting the language of the reason.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Setting the reason for status assignment. OPTIONAL.
     *
     * @param string $reason text of the reason
     */
    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Getting the reason for status assignment.
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
