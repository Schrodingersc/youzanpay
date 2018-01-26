# Youzan Pay

感谢 [Xu42](https://github.com/xu42/pay) 提供的思路。

[如何申请](https://blog.xu42.cn/2017/11/26/person-website-instant-payment-solution/)

### 安装

```
composer require damon/youzan-pay
```

### 创建订单

```php
use Damon\YouzanPay\YouzanPay;
use Damon\YouzanPay\QrCode\QrCode;

$app = new YouzanPay([
    'client_id' => 'f7409fedae526c55a4',
    'client_secret' => '8f647bf6a17b8bcfbe3103231281a87fc',
    'store_id' => '40040763'
]);

$qrcode = $app->qrcode->create([
    'qr_type' => QrCode::QR_TYPE_DYNAMIC,
    'qr_price' => 1,
    'qr_name' => '点付测试订单'
]);

echo '<img src="' . $qrcode['response']['qr_code'] . '" />';
```

### 检查支付状态

```php
if ($app->trade->isWaitPay()) {
    //
} elseif ($app->trade->isSuccessfully()) {
    $trade = $app->trade->getTrade();
} elseif ($app->trade->isClosed()) {

}
```
