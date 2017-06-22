<?php

namespace Struzik\EPPClient\Response\Session;

use Struzik\EPPClient\Response\AbstractCommonResponse;

/**
 * Object representation of the response of login command.
 */
class Login extends AbstractCommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS;
    }
}
