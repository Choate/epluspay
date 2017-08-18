<?php

namespace choate\epluspay\base;

interface PayOrdersInterface
{
    public function getShopId();

    public function setShopId($shopId);

    public function setNotifyUrl($notifyUrl);

    public function getNotifyUrl();
}