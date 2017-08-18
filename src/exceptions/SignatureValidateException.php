<?php
namespace choate\epluspay\exceptions;


class SignatureValidateException extends \Exception
{
    protected $message = '签名验证失败';
}