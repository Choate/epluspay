<?php

namespace choate\epluspay\exceptions;

class UnknownPropertyException extends \Exception
{

    public function getName()
    {
        return 'Unknown Property';
    }
}
