<?php

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

class BadUrlException extends \Exception
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

class NotFoundException extends \Exception
{
    public function __construct($message, $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}


