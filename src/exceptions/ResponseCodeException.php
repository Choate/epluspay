<?php

namespace choate\epluspay\exceptions;


use choate\epluspay\base\ParseResponse;
use choate\epluspay\Client;
use Exception;

class ResponseCodeException extends \Exception
{
    protected $statusCode;

    protected $reason = '不明确';

    public function __construct($statusCode, $message = null, $code = 0, \Exception $previous = null) {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getName() {
        if (isset(ParseResponse::$statuses[$this->getStatusCode()])) {
            return ParseResponse::$statuses[$this->getStatusCode()];
        }

        return '未知错误';
    }
}