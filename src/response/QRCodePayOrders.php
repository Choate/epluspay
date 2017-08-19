<?php

namespace choate\epluspay\response;

use choate\epluspay\base\ResponseInterface;
use choate\epluspay\helpers\ArrayHelper;

class QRCodePayOrders implements ResponseInterface
{
    private $orderNo;

    private $redirectUrl;

    public function load($data)
    {
        $orderNo = ArrayHelper::getValue($data, 'OrderNo');
        $redirectUrl = ArrayHelper::getValue($data, 'RedirectURL');
        $this->setOrderNo($orderNo);
        $this->setRedirectUrl($redirectUrl);
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }
}