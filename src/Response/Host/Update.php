<?php

namespace Struzik\EPPClient\Response\Host;

use Struzik\EPPClient\Response\CommonResponse;

/**
 * Object representation of the response of host updating command.
 */
class Update extends CommonResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->getResultCode() === self::RC_SUCCESS;
    }
}
