<?php

namespace choate\epluspay\base;

use choate\epluspay\exceptions\ResponseCodeException;
use choate\epluspay\exceptions\SignatureValidateException;
use choate\epluspay\helpers\Serializer;
use choate\epluspay\helpers\ArrayHelper;

class Response
{
    const SUCCESS_CODE       = '10000';

    const SUCCESS_CODE_ALIAS = '100';

    public static $statuses = [
        self::SUCCESS_CODE       => '成功',
        self::SUCCESS_CODE_ALIAS => '成功',
        '10100'                  => '系统内部错误',
        '10200'                  => '验证签名失败',
        '10300'                  => '解析报文错误',
        '10500'                  => '无效交易类型',
        '10700'                  => '参数不正确',
        '10900'                  => '渠道号不正确',
        '12000'                  => '版本号不正确',
        '20900'                  => '无权限调用此接口',
        '40000'                  => '业务操作失败',
    ];

    /**
     * 响应状态码
     *
     * @var string
     */
    private $code;

    /**
     * 响应数据
     *
     * @var string
     */
    private $data;

    /**
     * @var SignatureInterface
     */
    private $encryption;

    /**
     * 消息
     *
     * @var string
     */
    private $message;

    /**
     * 随机码
     *
     * @var string
     */
    private $rand_str;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * 校验签名
     *
     * @var string
     */
    private $signature;

    /**
     * 响应时间, 时间戳精确到毫秒
     *
     * @var int
     */
    private $timestamp;

    public function __construct($responseBody, RequestInterface $request, SignatureInterface $encryption)
    {
        $code = ArrayHelper::getValue($responseBody, 'code');
        $message = ArrayHelper::getValue($responseBody, 'message');
        $data = ArrayHelper::getValue($responseBody, 'data');
        $signature = ArrayHelper::getValue($responseBody, 'sign');
        $timestamp = ArrayHelper::getValue($responseBody, 'timestamp');
        $randStr = ArrayHelper::getValue($responseBody, 'randStr');

        $this->request = $request;
        $this->encryption = $encryption;
        $this->setCode($code);
        $this->setMessage($message);
        $this->setData($data);
        $this->setSignature($signature);
        $this->timestamp = $timestamp;
        $this->rand_str = $randStr;
        if ($this->getIsFailure()) {
            throw new ResponseCodeException($this->getCode(), $this->getMessage());
        }
        $this->validateSignature();
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    protected function setCode($code)
    {
        $this->code = $code;
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
    protected function setData($data)
    {
        $this->data = $data;
    }

    public function getIsFailure()
    {
        return !$this->getIsSuccess();
    }

    public function getIsSuccess()
    {
        return strcasecmp($this->getCode(), self::SUCCESS_CODE) === 0 || strcasecmp($this->getCode(), self::SUCCESS_CODE_ALIAS) === 0;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }

    public function getResponse()
    {
        $request = $this->request;
        $values = Serializer::unSerialize($this->getData());
        $request->setResponse($values);

        return $request->getResponse();
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
    protected function setSignature($signature)
    {
        $this->signature = $signature;
    }

    protected function validateSignature()
    {
        $values = [
            'code'      => $this->getCode(),
            'message'   => $this->getMessage(),
            'data'      => $this->getData(),
            'randStr'   => $this->rand_str,
            'timestamp' => $this->timestamp,
        ];
        ksort($values);
        $signatureData = urldecode(http_build_query($values));
        $valid = $this->encryption->validate($this->getSignature(), $signatureData);
        if ($valid === false) {
            throw new SignatureValidateException();
        }

        return true;
    }
}