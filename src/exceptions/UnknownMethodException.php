<?php

namespace choate\epluspay\exceptions;

class UnknownMethodException extends \BadMethodCallException
{
    public function getName()
    {
        return 'Unknown Method';
    }
}
