Payment provider for [Laravel](https://laravel.com/)
====

[![Latest Stable Version](https://poser.pugx.org/professionalweb/payment-laravel/v/stable)](https://packagist.org/packages/professionalweb/payment-laravel)
[![Code Climate](https://codeclimate.com/github/SergioMadness/payment-laravel/badges/gpa.svg)](https://codeclimate.com/github/SergioMadness/payment-laravel)
[![Dependency Status](https://www.versioneye.com/user/projects/573c5c00ce8d0e004130bd62/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/573c5c00ce8d0e004130bd62)
[![License](https://poser.pugx.org/professionalweb/payment-laravel/license)](https://packagist.org/packages/professionalweb/payment-laravel)
[![Latest Unstable Version](https://poser.pugx.org/professionalweb/payment-laravel/v/unstable)](https://packagist.org/packages/professionalweb/payment-laravel)

Project structure
-------------------
```
contracts/          Abstractions
drivers/            Payment drivers
    payonline/      PayOnline driver - https://payonline.ru/education/terms/
    tinkoff/        Tinkoff driver - https://oplata.tinkoff.ru/documentation/
    yandex/         Yandex.Kassa driver - https://tech.yandex.ru/money/doc/payment-solution/payment-notifications/payment-notifications-about-docpage/
```


Requirements
------------
 - PHP 5.5+

Dependencies
------------
 - [alcohol/iso4217](https://github.com/alcohol/iso4217)
 - [illuminate/support](https://github.com/illuminate/support)


Installation
------------
Module is available through [composer](https://getcomposer.org/)

composer require professionalweb/payment-laravel "dev-master"

Alternatively you can add the following to the `require` section in your `composer.json` manually:

```json
"professionalweb/payment-laravel": "^2.1"
```
Run `composer update` afterwards.


Initialization
--------------
##config/app.php
```php
<?php
return [
    'providers' => [
        ...
        \professionalweb\payment\PaymentProvider::class,
        ...
    ],
];
```

If you need only one specific payment provider:

PayOnline:
```php
return [
    'providers' => [
        ...
        \professionalweb\payment\PayOnlineProvider::class,
        ...
    ],
];
```

Tinkoff:
```php
return [
    'providers' => [
        ...
        \professionalweb\payment\TinkoffProvider::class,
        ...
    ],
];
```

Yandex.Kassa:
```php
return [
    'providers' => [
        ...
        \professionalweb\payment\YandexProvider::class,
        ...
    ],
];
```

##config/payment.php
```php
<?php
return [
    'default_driver' => env('DEFAULT_PAYMENT_SYSTEM', \professionalweb\payment\PaymentProvider::PAYMENT_TINKOFF),
    'tinkoff'        => [
        'merchantId' => env('TINKOFF_MERCHANT_ID'),
        'secretKey'  => env('TINKOFF_SECRET_KEY'),
        'successURL' => env('TINKOFF_SUCCESS_URL', '/'),
        'failURL'    => env('TINKOFF_FAIL_URL', '/'),
        'apiUrl'     => env('TINKOFF_API_URL', 'https://securepay.tinkoff.ru/rest/'),
    ],
    'payonline'      => [
        'merchantId' => env('PAYONLINE_MERCHANT_ID'),
        'secretKey'  => env('PAYONLINE_SECRET_KEY'),
        'successURL' => env('PAYONLINE_SUCCESS_URL', '/'),
        'failURL'    => env('PAYONLINE_FAIL_URL', '/'),
    ],
    'yandex'      => [
        'merchantId' => env('YANDEX_SHOP_ID'),
        'scid'       => env('YANDEX_SCID'),
        'secretKey'  => env('YANDEX_SECRET_KEY'),
        'successURL' => env('YANDEX_SUCCESS_URL', '/'),
        'failURL'    => env('YANDEX_FAIL_URL', '/'),
        'isTest'     => false,
    ],
];
```

Using
-----------
At first user must be redirected to payment system page:
```php
<?php

public function action(PayService $paymentService) {
    redirect()->to(
        $paymentService->getPaymentLink($order->id,
            $payment->id,
            $payment->amount,
            $payment->currency,
            $successfulPaymentReturnUrl, //Tinkoff doesn't need it
            $failedPaymentReturnUrl, //Tinkoff doesn't need it
            $description
        );
    );
}
```

Then you need to handle async response:
```php
<?php

public function responseHandler(PayService $paymentService) {
    if($paymentService->setResponse($this->getRequest()->all())->isSuccess()) {
        $orderId = $paymentService->getOrderId();
        $status = $paymentService->getStatus();
        $amount = $paymentService->getAmount();
        $errorCode = $paymentService->getErrorCode();
        $pan = $paymentService->getPan();
        $paymentDate = $paymentService->getDateTime();
        $transactionId = $paymentService->getTransactionId();
        $provider = $service->getProvider();

        // Update order, payment record, etc...
    } else {
        // something else
    }
}
```



The MIT License (MIT)
---------------------

Copyright (c) 2016 Sergey Zinchenko, [Professional web](http://web-development.pw)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.