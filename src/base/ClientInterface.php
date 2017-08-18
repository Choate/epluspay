<?php


namespace choate\epluspay\base;

interface ClientInterface
{
    public function run(RequestInterface $request);
}