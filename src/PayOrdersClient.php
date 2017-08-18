<?php

namespace choate\epluspay;

use choate\epluspay\base\ClientInterface;
use choate\epluspay\base\PayOrdersInterface;
use choate\epluspay\base\RequestInterface;
use choate\epluspay\exceptions\InvalidArgumentException;

class PayOrdersClient implements ClientInterface
{
    /**
     * @var \choate\epluspay\Client
     */
    private $client;

    /**
     * 回调地址
     *
     * @var string
     */
    private $notifyUrl;

    /**
     * 商铺ID
     *
     * @var string
     */
    private $shopId;

    public function __construct($shopId, $notifyUrl, Client $client)
    {
        $this->setShopId($shopId);
        $this->setNotifyUrl($notifyUrl);
        $this->setClient($client);
    }

    /**
     * @return \choate\epluspay\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \choate\epluspay\Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * @param string $notifyUrl
     */
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @param string $shopId
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    public function run(RequestInterface $request)
    {
        if (!$request instanceof PayOrdersInterface) {
            throw new InvalidArgumentException('The value of $request does not implement PayOrderInterface');
        }
        $request->setShopId($this->getShopId());
        $request->setNotifyUrl($this->getNotifyUrl());

        return $this->getClient()->run($request);
    }

}