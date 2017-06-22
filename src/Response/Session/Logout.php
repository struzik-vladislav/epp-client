<?php

namespace Struzik\EPPClient\Response\Session;

use Struzik\EPPClient\Response\AbstractCommonResponse;

/**
 * Object representation of the response of logout command.
 */
class Logout extends AbstractCommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS_ENDING_SESSION;
    }
}
