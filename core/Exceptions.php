<?php

namespace Bloum;

class BabUrlException extends \Exception
{
    public function __construct($message, $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LoggerException extends \Exception
{
    public function __construct($message, $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

