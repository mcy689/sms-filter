# sms-filter

## Requirements

* PHP > 7.0

## Installing

```html
composer require mcy689/sms-filter -v
```

## Usage

### Code

```php
<?php
require 'vendor/autoload.php';
try {
    $msg = '您尾号1060的账户于7月26日网银转账转出人民币10.00元，收款人宋小宝，活期存款账户余额人民币214.98元，【平安银行】';
    $code = '95511';
    var_dump(Mcy689\SmsFilter\Sms::analyse($code, $msg));
    die;
} catch (\Exception $e) {
    var_dump($e->getMessage());
    die;
}
```

### Output

```htmll
array(5) {
  ["card"]=>
  string(4) "1060"
  ["amount"]=>
  string(4) "1000"
  ["bank"]=>
  string(12) "平安银行"
  ["name"]=>
  string(9) "宋小宝"
  ["type"]=>
  int(2)
}
```



