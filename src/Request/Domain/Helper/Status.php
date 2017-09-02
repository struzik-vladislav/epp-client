<?php

namespace Struzik\EPPClient\Request\Domain\Helper;

/**
 * Parametres aggregation for domain status structure.
 */
class Status
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $reason;

    /**
     * Setting the status. REQUIRED.
     *
     * @param string $status the value of the status constant
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Getting the status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setting the language of the reason. OPTIONAL.
     *
     * @param string|null $language ISO code of the language
     *
     * @return self
     */
    public function setLanguage($language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Getting the language of the reason.
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Setting the reason for status assignment. OPTIONAL.
     *
     * @param string|null $reason text of the reason
     *
     * @return self
     */
    public function setReason($reason = null)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Getting the reason for status assignment.
     *
     * @return string|null
     */
    public function getReason()
    {
        return $this->reason;
    }
}
