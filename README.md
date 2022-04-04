Payment provider for [Laravel](https://laravel.com/)
====

[![Latest Stable Version](https://poser.pugx.org/professionalweb/payment-laravel/v/stable)](https://packagist.org/packages/professionalweb/payment-laravel)
[![Code Climate](https://codeclimate.com/github/SergioMadness/payment-laravel/badges/gpa.svg)](https://codeclimate.com/github/SergioMadness/payment-laravel)
[![License](https://poser.pugx.org/professionalweb/payment-laravel/license)](https://packagist.org/packages/professionalweb/payment-laravel)
[![Latest Unstable Version](https://poser.pugx.org/professionalweb/payment-laravel/v/unstable)](https://packagist.org/packages/professionalweb/payment-laravel)


Requirements
------------
 - PHP 7.2+

Dependencies
------------
 - [laravel/laravel](https://github.com/laravel/laravel)


Installation
------------
Module is available through [composer](https://getcomposer.org/)

composer require professionalweb/payment-laravel "dev-master"

Alternatively you can add the following to the `require` section in your `composer.json` manually:

```json
"professionalweb/payment-laravel": "^4.0"
```
Run `composer update` afterwards.

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
            PayService::PAYMENT_TYPE_CARD
            $successfulPaymentReturnUrl,
            $failedPaymentReturnUrl,
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

Receipts
--------
To send receipt to IRS
```php
/**
 * Prepare Receipt
 *
 * @param Order $order
 *
 * @return Receipt
 */
public function prepareReceipt(Order $order)
{
    $receipt = new Receipt($order->user->email);
    /** @var Item $item */
    foreach ($order->items as $item) {
        $receipt->addItem(new ReceiptItem($item->name, $item->qty, $item->price, config('payment.tax')));
    }

    return $receipt;
}
```

PayOnline has separate service to send register receipts and send to users.
```php
use professionalweb\payment\contracts\ReceiptService;

$receipt = new Receipt($order->user->email);
$receipt->setTransactionId($transactionIdFromPayOnlineResponse);
/** @var Item $item */
foreach ($order->items as $item) {
    $receipt->addItem(new ReceiptItem($item->name, $item->qty, $item->price, config('payment.tax')));
}

app(ReceiptService::class)->sendReceipt(
    $this->prepareReceipt()
);
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