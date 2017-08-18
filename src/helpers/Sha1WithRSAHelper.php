<?php
namespace choate\epluspay\helpers;

use choate\epluspay\base\SignatureInterface;

class Sha1WithRSAHelper implements SignatureInterface
{
    /**
     * @var string|\Closure 私钥
     */
    private $privateKey;

    /**
     * @var string|\Closure 公钥
     */
    private $publicKey;

    public function __construct($privateKey, $publicKey)
    {
        $this->setPrivateKey($privateKey);
        $this->setPublicKey($publicKey);
    }

    public function generate($value) {
//        echo "签名数据：\n";
//        echo $value;
//        echo "\n";
//        echo "Sha1：\n";
        // 对要签名的数据进行
        $hash = sha1($value);
//        $hash = $value;
//        echo $hash;
//        echo "\n";
        // 获取私钥
        $privateKey = $this->getPrivateKey();
        $privateKeyId = openssl_get_privatekey($privateKey);
        // 对数据进行签名
        openssl_sign($hash, $signature, $privateKeyId);
        // 释放资源
        openssl_free_key($privateKeyId);
        //把二进制数据转换成十六进制
        $result = bin2hex($signature);;

        return $result;
    }

    /**
     * @return array
     */
    public function getErrors() {
        $result = [];
        while (($msg = openssl_error_string()) != false) {
            array_push($result, $msg);
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getPrivateKey() {
        return $this->privateKey;
    }

    /**
     * @param mixed $privateKey
     */
    public function setPrivateKey($privateKey) {
        if ($privateKey instanceof \Closure) {
            $key = call_user_func($privateKey);
        } else {
            $key = file_get_contents($privateKey);
        }

        $this->privateKey = $key;
    }

    /**
     * @return mixed
     */
    public function getPublicKey() {
        return $this->publicKey;
    }

    /**
     * @param mixed $publicKey
     */
    public function setPublicKey($publicKey) {
        if ($publicKey instanceof \Closure) {
            $key = call_user_func($publicKey);
        } else {
            $key = file_get_contents($publicKey);
        }

        $this->publicKey = $key;
    }

    public function validate($signature, $data) {
        $hash = sha1($data);
        $publicKey = $this->getPublicKey();
        $publicKeyId = openssl_get_publickey($publicKey);
        $_signature = hex2bin($signature);
        $result = openssl_verify($hash, $_signature, $publicKeyId);
        openssl_free_key($publicKeyId);

        return $result > 0;
    }
}