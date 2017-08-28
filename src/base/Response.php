<?php

namespace choate\epluspay\base;

use choate\epluspay\exceptions\ResponseCodeException;
use choate\epluspay\exceptions\SignatureValidateException;
use choate\epluspay\exceptions\UnknownPropertyException;
use choate\epluspay\helpers\ArrayHelper;
use choate\epluspay\helpers\Formatter;

class Response extends Object
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
     * @var array
     */
    private $dataParams;

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
    private $randStr;

    /**
     * 校验签名
     *
     * @var string
     */
    private $signature;

    /**
     * 响应主体
     *
     * @var string
     */
    private $stream;

    /**
     * 响应时间, 时间戳精确到毫秒
     *
     * @var int
     */
    private $timestamp;

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

    public function getDataParams($name = null, $defaultValue = null)
    {
        if (is_null($this->dataParams)) {
            $this->dataParams = Formatter::decode($this->getData());
        }

        if (is_null($name)) {
            return $this->dataParams;
        }

        return ArrayHelper::getValue($this->dataParams, $name, $defaultValue);
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
    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;
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

    /**
     * @return string
     */
    public function getRandStr()
    {
        return $this->randStr;
    }

    /**
     * @param string $randStr
     */
    public function setRandStr($randStr)
    {
        $this->randStr = $randStr;
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

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return self::$statuses;
    }

    /**
     * @param array $statuses
     */
    public static function setStatuses(array $statuses)
    {
        self::$statuses = $statuses;
    }

    /**
     * @return string
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @param string $stream
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function init()
    {
        parent::init();
        if (is_null($this->stream)) {
            throw new UnknownPropertyException('不明确的"stream"属性');
        }
        if (is_null($this->encryption) || !$this->encryption instanceof SignatureInterface) {
            throw new UnknownPropertyException('不明确的"encryption"属性');
        }
        $map = [
            'code'      => 'code',
            'sign'      => 'signature',
            'message'   => 'message',
            'timestamp' => 'timestamp',
            'randStr'   => 'randStr',
            'data'      => 'data',
            'error'     => 'code',
        ];
        Object::configure($this, $this->getStream(), $map);
        $this->validate();

    }

    protected function validate()
    {
        if ($this->getIsFailure()) {
            throw new ResponseCodeException($this->getCode(), $this->getMessage());
        }

        $values = [
            'code'      => $this->getCode(),
            'message'   => $this->getMessage(),
            'data'      => $this->getData(),
            'randStr'   => $this->getRandStr(),
            'timestamp' => $this->getTimestamp(),
        ];
        ksort($values);
        $signatureData = urldecode(http_build_query($values));
        $valid = $this->getEncryption()->validate($this->getSignature(), $signatureData);
        if ($valid === false) {
            throw new SignatureValidateException();
        }

        return true;
    }
}