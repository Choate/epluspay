<?php

namespace choate\epluspay\base;

use choate\epluspay\exceptions\UnknownPropertyException;
use choate\epluspay\helpers\ArrayHelper;
use choate\epluspay\helpers\Formatter;

class Notify extends Object
{
    private $callbackApiUrl;

    private $channelNo;

    private $data;

    private $encryption;

    private $lang;

    private $optType;

    private $publicKeyNo;

    private $randStr;

    private $scheme;

    private $serverName;

    private $serverPort;

    private $signature;

    private $stream;

    private $timestamp;

    private $version;

    private $dataParams;

    const STATUS_SUCCESS = 0;

    const STATUS_FAILURE = 1;

    /**
     * @return mixed
     */
    public function getCallbackApiUrl()
    {
        return $this->callbackApiUrl;
    }

    /**
     * @param mixed $callbackApiUrl
     */
    public function setCallbackApiUrl($callbackApiUrl)
    {
        $this->callbackApiUrl = $callbackApiUrl;
    }

    /**
     * @return mixed
     */
    public function getChannelNo()
    {
        return $this->channelNo;
    }

    /**
     * @param mixed $channelNo
     */
    public function setChannelNo($channelNo)
    {
        $this->channelNo = $channelNo;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * @param mixed $encryption
     */
    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getOptType()
    {
        return $this->optType;
    }

    /**
     * @param mixed $optType
     */
    public function setOptType($optType)
    {
        $this->optType = $optType;
    }

    /**
     * @return mixed
     */
    public function getPublicKeyNo()
    {
        return $this->publicKeyNo;
    }

    /**
     * @param mixed $publicKeyNo
     */
    public function setPublicKeyNo($publicKeyNo)
    {
        $this->publicKeyNo = $publicKeyNo;
    }

    /**
     * @return mixed
     */
    public function getRandStr()
    {
        return $this->randStr;
    }

    /**
     * @param mixed $randStr
     */
    public function setRandStr($randStr)
    {
        $this->randStr = $randStr;
    }

    /**
     * @return mixed
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param mixed $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return mixed
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * @param mixed $serverName
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
    }

    /**
     * @return mixed
     */
    public function getServerPort()
    {
        return $this->serverPort;
    }

    /**
     * @param mixed $serverPort
     */
    public function setServerPort($serverPort)
    {
        $this->serverPort = $serverPort;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @param mixed $stream
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getDataParams($name = null, $defaultValue = null) {
        if (is_null($this->dataParams)) {
            $this->dataParams = Formatter::decode($this->getData());
        }

        if (is_null($name)) {
            return $this->dataParams;
        }

        return ArrayHelper::getValue($this->dataParams, $name, $defaultValue);
    }

    public function init()
    {
        parent::init();

        if (is_null($this->stream) || !is_array($this->stream)) {
            throw new UnknownPropertyException('不明确的"stream"属性');
        }
        if (is_null($this->encryption) || !$this->encryption instanceof SignatureInterface) {
            throw new UnknownPropertyException('不明确的"encryption"属性');
        }
        $map = [
            'channelNo'   => 'channelNo',
            'optType'     => 'optType',
            'lang'        => 'lang',
            'timestamp'   => 'timestamp',
            'randStr'     => 'randStr',
            'publicKeyNo' => 'publicKeyNo',
            'data'        => 'data',
            'sign'        => 'signature',
            'version'     => 'version',
        ];
        Object::configure($this, $this->getStream(), $map);
    }

    public function validator()
    {
        return true;
    }

    public function getResponseContent($message, $code) {
        if ($code === self::STATUS_SUCCESS) {
            $status = 'success';
        } else {
            $status = 'failure';
        }
        $data = ['status' => $status];
        $result = json_encode(['error' => 1, 'message' => $message, 'data' => Formatter::encode($data)]);

        return $result;
    }
}