<?php

namespace App\Service;

use Psr\Log\LoggerInterface;


class SmsCodeGenerator
{
    /**
     * @return string
     */
    public function getCode()
    {
        $rand = rand(1000, 9999);
        //return strval($rand);
        return '1111';
    }
}
