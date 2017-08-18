<?php
namespace choate\epluspay\response;


class PayOrders
{
    const SUCCESS = 1;
    const FAILURE = 0;
    const UNTREATED = -1;
    const UNKNOWN = -2;
    const PROCESSING = 2;
    const CANCEL = 3;
    const CLOSED = 4;
    const REFUND = 5;

    /**
     * 订单编号
     *
     * @var string
     */
    private $orderNo;

    /**
     * 订单状态
     *
     * @var int
     */
    private $orderStatus;


    /**
     * 二维码字符串
     *
     * @var string
     */
    private $qrCode;

    /**
     * 订单交易号
     *
     * @var string
     */
    private $tradeNo;

    public function __construct($orderNo, $orderStatus, $tradeNo, $qrCode = null) {
        $this->setOrderNo($orderNo);
        $this->setOrderStatus($orderStatus);
        $this->setTradeNo($tradeNo);
        $this->setQrCode($qrCode);
    }

    /**
     * @return mixed
     */
    public function getOrderNo() {
        return $this->orderNo;
    }

    /**
     * @return mixed
     */
    public function getOrderStatus() {
        return $this->orderStatus;
    }

    /**
     * @return mixed
     */
    public function getQrCode() {
        return $this->qrCode;
    }

    /**
     * @return mixed
     */
    public function getTradeNo() {
        return $this->tradeNo;
    }

    /**
     * @param mixed $orderNo
     */
    private function setOrderNo($orderNo) {
        $this->orderNo = $orderNo;
    }

    /**
     * @param mixed $orderStatus
     */
    private function setOrderStatus($orderStatus) {
        $this->orderStatus = $orderStatus;
    }

    /**
     * @param mixed $qrCode
     */
    private function setQrCode($qrCode) {
        $this->qrCode = $qrCode;
    }

    /**
     * @param mixed $tradeNo
     */
    private function setTradeNo($tradeNo) {
        $this->tradeNo = $tradeNo;
    }

    public function getIsSuccess() {
    }

}