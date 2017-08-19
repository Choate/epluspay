<?php


namespace choate\epluspay\base;

interface ClientInterface
{
    /**
     * @param \choate\epluspay\base\RequestInterface $request
     *
     * @return \choate\epluspay\base\ParseResponse
     */
    public function run(RequestInterface $request);
}