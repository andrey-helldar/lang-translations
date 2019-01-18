<?php

namespace Helldar\LangTranslations\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HandlerException extends HttpException
{
    public function __construct($message, \Exception $previous = null, $code = 0)
    {
        parent::__construct($code, $message, $previous, [], $code);
    }
}
