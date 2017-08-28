<?php

namespace choate\epluspay;

use choate\epluspay\base\PayOrdersInterface;
use choate\epluspay\base\RequestInterface;
use choate\epluspay\exceptions\InvalidArgumentException;
use choate\epluspay\exceptions\UnknownPropertyException;

class EPlusPayOrder extends EPlusPay
{
    private $notifyUrl;

    private $shopId;

    public function init() {
        if (is_null($this->notifyUrl)) {
            throw new UnknownPropertyException('不明确的"notifyUrl"属性');
        }
        if (is_null($this->shopId)) {
            throw new UnknownPropertyException('不明确的"shopId"属性');
        }
        parent::init();
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * @param mixed $notifyUrl
     */
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    /**
     * @return mixed
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @param mixed $shopId
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    public function run(RequestInterface $request, $randStr)
    {
        if (!$request instanceof PayOrdersInterface) {
            throw new InvalidArgumentException('无效的"request"参数，该参数必须要继承"PayOrderInterface"接口');
        }
        $request->setShopId($this->getShopId());
        $request->setNotifyUrl($this->getNotifyUrl());

        return parent::run($request, $randStr);
    }

}