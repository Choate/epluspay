# 招行E+支付

# 安装
基于composer安装

`php composer.phar require choate/epluspay`

# 说明

## 生成收款二维码接口使用

```php
$channelNo = 'xxxx';
$serviceUrl = 'http://example.com';
$publicKeyNo = 'xxxx';
$lang = 'zh_CN';
$privateKey = 'file_path or \Closure';
$publicKey = 'file_path or \Closure';
$requestId = '请求编号';
$orderNo = '流水号';
$amount = 0.01;
$channelType = QRCodePayOrders::CHANNEL_TYPE_WECHAT;
$encryption = new Sha1WithRSAHelper($privateKey, $publicKey);
// 接口使用
$client = new Client($channelNo, $serverUrl, $publicKeyNo, $lang, $encryption);
$payOrdersClient = new PayOrdersClient($shopId, $notifyUrl, $client);
// 构造支付类型
$payOrders = new QRCodePayOrders($requestId, $orderNo, $amount, $channelType);
// 执行支付接口
$response = $payOrders->run($payOrdersClient);
```