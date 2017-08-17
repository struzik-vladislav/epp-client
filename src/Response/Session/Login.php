<?php

namespace Struzik\EPPClient\Response\Session;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of login command.
 */
class Login extends CommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS;
    }
}
