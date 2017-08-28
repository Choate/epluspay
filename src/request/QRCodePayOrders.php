<?php

namespace choate\epluspay\request;

use choate\epluspay\base\PayOrdersInterface;
use choate\epluspay\base\RequestInterface;

class QRCodePayOrders implements RequestInterface, PayOrdersInterface
{
    const CHANNEL_TYPE_ALIPAY = 11;

    const CHANNEL_TYPE_WECHAT = 12;

    private $amount;

    private $channelType;

    private $lockType;

    private $notifyUrl;

    private $operatorId;

    private $orderNo;

    private $remark;

    private $requestId;

    private $shopId;

    private $templateId;

    public function __construct($requestId, $orderNo, $amount, $channelType)
    {
        $this->setRequestId($requestId);
        $this->setOrderNo($orderNo);
        $this->setAmount($amount);
        $this->setChannelType($channelType);
    }

    public function execute()
    {
        return [
            'optType'     => $this->getScenario(),
            'requestId'   => $this->getRequestId(),
            'ShopId'      => $this->getShopId(),
            'OrderNo'     => $this->getOrderNo(),
            'Amount'      => $this->getAmount(),
            'OperatorId'  => $this->getOperatorId(),
            'ChannelType' => $this->getChannelType(),
            'LockType'    => $this->getLockType(),
            'TemplateId'  => $this->getTemplateId(),
            'Remark'      => $this->getRemark(),
            'NotifyUrl'   => $this->getNotifyUrl(),
        ];
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getChannelType()
    {
        return $this->channelType;
    }

    /**
     * @param mixed $channelType
     */
    public function setChannelType($channelType)
    {
        $this->channelType = $channelType;
    }

    /**
     * @return mixed
     */
    public function getLockType()
    {
        if (is_null($this->lockType) && is_null($this->getRemark())) {
            $this->lockType = 2;
        } elseif (is_null($this->lockType)) {
            $this->lockType = 1;
        }

        return $this->lockType;
    }

    /**
     * @param mixed $lockType
     */
    public function setLockType($lockType)
    {
        $this->lockType = $lockType;
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
    public function getOperatorId()
    {
        return $this->operatorId;
    }

    /**
     * @param mixed $operatorId
     */
    public function setOperatorId($operatorId)
    {
        $this->operatorId = $operatorId;
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
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    public function getScenario()
    {
        return 'FT_P202';
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

    /**
     * @return mixed
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * @param mixed $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    public function getVersion()
    {
        return '1.0';
    }
}