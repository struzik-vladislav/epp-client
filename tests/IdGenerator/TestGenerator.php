<?php

namespace Struzik\EPPClient\Tests\IdGenerator;

use Struzik\EPPClient\IdGenerator\IdGeneratorInterface;
use Struzik\EPPClient\Request\RequestInterface;

class TestGenerator implements IdGeneratorInterface
{
    public const TEST_ID = 'TEST-REQUEST-ID';

    public function generate(RequestInterface $request): string
    {
        return self::TEST_ID;
    }
}
