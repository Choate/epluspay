<?php
namespace choate\epluspay\base;


interface RequestInterface
{
    public function getScenario();

    public function execute();
}