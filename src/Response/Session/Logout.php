<?php

namespace Struzik\EPPClient\Response\Session;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of logout command.
 */
class Logout extends CommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS_ENDING_SESSION;
    }
}
