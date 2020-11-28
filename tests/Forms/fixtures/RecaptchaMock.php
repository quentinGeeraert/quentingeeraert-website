<?php

namespace App\Tests\Forms\fixtures;

use ReCaptcha\Response;

class RecaptchaMock
{
    /**
     * @var bool
     */
    public $nextSuccess = true;

    /**
     * @var array<string>
     */
    public $nextErrorCodes = [];

    /**
     * @param mixed $response
     * @param mixed $remoteIp
     */
    public function verify($response, $remoteIp = null): Response
    {
        return new Response($this->nextSuccess, $this->nextErrorCodes);
    }
}
