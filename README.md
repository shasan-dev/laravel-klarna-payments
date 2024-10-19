Here's a beautified version of the README for the Laravel Klarna Payments package:

---

# Laravel Klarna Payments

This package integrates Klarna Payments into your Laravel application.

## Installation

1. Install the package via Composer:
   ```bash
   composer require shasan-dev/laravel-klarna-payments
   ```

2. Publish the configuration file:
   ```bash
   php artisan vendor:publish --provider="LaravelKlarna\KlarnaPayments\KlarnaPaymentsServiceProvider" --tag="klarna-payments-config"
   ```

3. Update your `.env` file:
   ```
   KLARNA_USER_ID=your_user_id
   KLARNA_PASSWORD=your_password
   KLARNA_TESTMODE=true_or_false
   KLARNA_REGION=NA/OC/EU (default EU)
   ```

## Usage

### Create a Controller
```bash
php artisan make:controller KlarnaController
```

### Define Routes
```php
Route::get('payment/klarna-checkout', [KlarnaController::class, 'payment'])->name('klarna.checkout');
Route::get('payment/klarna-success', [KlarnaController::class, 'paymentSuccess'])->name('klarna.success');
```

### Payment Logic
In `KlarnaController`, handle the payment logic as follows:

```php
use LaravelKlarna\KlarnaPayments\Facades\KlarnaPayments;

public function payment() {
    $data = [
        'order_amount' => 20 * 100,
        'order_lines' => [
            [
                'name' => 'Running shoe',
                'quantity' => 1,
                'quantity_unit' => 'pcs',
                'total_amount' => 20 * 100,
                'type' => 'physical',
                'unit_price' => 20 * 100,
            ]
        ],
        'purchase_country' => 'US',
        'purchase_currency' => 'USD',
        'intent' => 'buy',
    ];

    $sessionId = KlarnaPayments::createSession($data)->json()['session_id'];

    $redirectUrl = KlarnaPayments::createHppSession($sessionId, [
        'success' => route('klarna.success') . "?sid={{session_id}}&order_id={{order_id}}",
        'cancel' => route('home'),
        'back' => route('home'),
        'failure' => route('home') . "?sid={{session_id}}",
        'error' => route('home') . "?sid={{session_id}}",
    ])->json()['redirect_url'];

    return redirect()->to($redirectUrl);
}

public function paymentSuccess(Request $request) {
    $checkPayment = KlarnaPayments::checkPayment($request->get('order_id'));

    if ($checkPayment->json()['status'] === 'AUTHORIZED') {
        return 'Payment Success. Reference No. ' . $checkPayment->json()['klarna_reference'];
    }

    return 'Payment Not Successful';
}
```

### Final Steps

Visit `/payment/klarna-checkout` to initiate a payment. 

If you find this package helpful, don't forget to star it! Thanks.

## License

MIT License.

---

