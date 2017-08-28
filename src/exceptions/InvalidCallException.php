<?php

namespace choate\epluspay\exceptions;

class InvalidCallException extends \BadMethodCallException
{
    public function getName()
    {
        return 'Invalid Call';
    }
}
