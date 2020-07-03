<?php
namespace core\Exception;
use \Exception as Exception;

class SystemException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

    }

    public function output()
    {
        error(self::getMessage(), self::getCode());
    }
}