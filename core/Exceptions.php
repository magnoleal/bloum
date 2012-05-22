<?php

namespace Bloum;

/**
 * Arquivo que guarda todas as exceções que podem ser lançadas pelo framework<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 12 de Maio de 2012
 */

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


