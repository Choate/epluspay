<?php
namespace choate\epluspay\base;


interface RequestInterface
{
    public function getScenario();

    public function getVersion();

    public function execute();

    public function setResponse($response);

    public function getResponse();

    public function buildResponse();
}