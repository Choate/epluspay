<?php

namespace choate\epluspay;

use choate\epluspay\base\Notify;
use choate\epluspay\base\Object;
use choate\epluspay\base\Response;
use choate\epluspay\base\RequestInterface;
use \choate\epluspay\base\SignatureInterface;
use choate\epluspay\exceptions\UnknownPropertyException;
use choate\epluspay\helpers\Formatter;
use choate\epluspay\helpers\Sha1WithRSAHelper;

class EPlusPay extends Object
{
    /**
     * @var string
     */
    private $channelNo;

    /**
     * 加密对象
     *
     * @var SignatureInterface
     */
    private $encryption;

    /**
     * 语言 zh_CH 或 en_US
     *
     * @var string
     */
    private $lang;

    /**
     * 公钥编码
     *
     * @var string
     */
    private $publicKeyNo;

    /**
     * 服务器地址
     *
     * @var string
     */
    private $serverUrl;

    /**
     * 接口版本
     * 
     * @var string
     */
    private $version;

    /**
     * 随机字符串
     *
     * @var string
     */
    private $randStr;

    /**
     * 请求编码
     * 
     * @var string
     */
    private $optType;

    /**
     * 公钥
     *
     * @var string
     */
    private $privateKey;

    /**
     * 私钥
     *
     * @var string
     */
    private $publicKey;

    public function init() {
        if (is_null($this->serverUrl)) {
            throw new UnknownPropertyException('不明确的"serverUrl"属性');
        }
        if (is_null($this->channelNo)) {
            throw new UnknownPropertyException('不明确的"channelNo"属性');
        }
        if (is_null($this->publicKeyNo)) {
            throw new UnknownPropertyException('不明确的"publicKeyNo"属性');
        }
        if (is_null($this->version)) {
            throw new UnknownPropertyException('不明确的"version"属性');
        }
        if (is_null($this->privateKey)) {
            throw new UnknownPropertyException('不明确的"privateKey"属性');
        }
        if (is_null($this->publicKey)) {
            throw new UnknownPropertyException('不明确的"publicKey"属性');
        }
    }

    public function run(RequestInterface $request, $randStr)
    {
        $this->setRandStr($randStr);
        $requestBody = $this->generateRequestBody($request);
        $this->generateSignature($requestBody);
        // 构造json格式请求数据
        $requestBodyJson = json_encode($requestBody);
        // 指定请求媒体类型
        $headers = ['Content-type' => 'application/json'];
        // 请求接口
        $response = \Requests::post($this->getServerUrl(), $headers, $requestBodyJson);
        $response->throw_for_status(false);
        $responseBody = json_decode($response->body, true);
        $configs = [
            'stream' => $responseBody,
            'encryption' => $this->getEncryption()
        ];

        return new Response($configs);
    }

    private function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    private function generateRequestBody(RequestInterface $request) {
        // 序列化数据
        $data = array_filter($request->execute());
        $values = Formatter::encode($data);
        // 请求主体数据
        $requestBody = [
            'channelNo'   => $this->getChannelNo(),
            'lang'        => $this->getLang(),
            'randStr'     => $this->getRandStr(),
            'publicKeyNo' => $this->getPublicKeyNo(),
            'data'        => $values,
            'version'     => $this->getVersion(),
            'timestamp'   => $this->getTimestamp(),
            'optType'     => $request->getScenario(),
        ];

        return $requestBody;
    }

    private function generateSignature(&$requestBody) {
        // 对数据进行排序
        ksort($requestBody);
        // 对请求主体数据进行签名
        $signature = $this->getEncryption()->generate(urldecode(http_build_query($requestBody)));
        // 写入签名
        $requestBody['sign'] = $signature;

        return $signature;
    }

    /**
     * @return string
     */
    public function getChannelNo()
    {
        return $this->channelNo;
    }

    /**
     * @param $channelNo
     */
    public function setChannelNo($channelNo)
    {
        $this->channelNo = $channelNo;
    }

    /**
     * @return SignatureInterface
     */
    public function getEncryption() 
    {
        if (is_null($this->encryption)) {
            $this->encryption = new Sha1WithRSAHelper($this->getPrivateKey(), $this->getPublicKey());
        }

        return $this->encryption;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    public function getPublicKeyNo()
    {
        return $this->publicKeyNo;
    }

    /**
     * @param $publicKeyNo
     */
    public function setPublicKeyNo($publicKeyNo)
    {
        $this->publicKeyNo = $publicKeyNo;
    }

    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @param $serverUrl
     */
    public function setServerUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getRandStr()
    {
        return $this->randStr;
    }

    /**
     * @param $randStr
     */
    public function setRandStr($randStr)
    {
        $this->randStr = $randStr;
    }

    /**
     * @return string
     */
    public function getOptType()
    {
        return $this->optType;
    }

    /**
     * @param $optType
     */
    public function setOptType($optType)
    {
        $this->optType = $optType;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param array $requestBody
     *
     * @return \choate\epluspay\base\Notify
     */
    public function buildNotify($requestBody) {
        $config = [
            'stream' => $requestBody,
            'encryption' => $this->getEncryption(),
        ];
        $notify = new Notify($config);

        return $notify;
    }
}