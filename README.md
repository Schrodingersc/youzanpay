# Youzan Pay

### 创建订单

```php
use Damon\YouzanPay\YouzanPay;
use Damon\YouzanPay\QrCode\QrCode;

$app = new YouzanPay([
    'client_id' => 'f7409fedae389c55a4',
    'client_secret' => '8f647bf6a17b8bcfbe388231281a87fc',
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
