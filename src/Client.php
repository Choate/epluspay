<?php

namespace choate\epluspay;

use choate\epluspay\base\ClientInterface;
use choate\epluspay\base\RequestInterface;
use choate\epluspay\base\Response;
use choate\epluspay\base\SignatureInterface;
use choate\epluspay\helpers\Serializer;
use Ramsey\Uuid\Uuid;

class Client implements ClientInterface
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

    public function __construct($channelNo, $serverUrl, $publicKeyNo, $lang, $encryption)
    {
        $this->setChannelNo($channelNo);
        $this->setServerUrl($serverUrl);
        $this->setPublicKeyNo($publicKeyNo);
        $this->setLang($lang);
        $this->setEncryption($encryption);
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
     * @return SignatureInterface
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * @param SignatureInterface $encryption
     */
    public function setEncryption(SignatureInterface $encryption)
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
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @param mixed $serverUrl
     */
    public function setServerUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;
    }

    public function run(RequestInterface $request)
    {
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

        return new Response($responseBody, $request, $this->getEncryption());
    }

    private function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    private function generateRequestBody(RequestInterface $request) {
        // 序列化数据
        $data = array_filter($request->execute());
        $values = Serializer::serialize($data);
        // 请求主体数据
        $requestBody = [
            'channelNo'   => $this->getChannelNo(),
            'lang'        => $this->getLang(),
            'randStr'     => Uuid::uuid4()->toString(),
            'publicKeyNo' => $this->getPublicKeyNo(),
            'data'        => $values,
            'version'     => $request->getVersion(),
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
}