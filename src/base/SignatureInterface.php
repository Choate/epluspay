<?php
namespace choate\epluspay\base;


interface SignatureInterface
{
    public function generate($data);

    public function validate($signature, $data);
}