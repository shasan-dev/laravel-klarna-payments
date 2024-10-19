# A laravel package to utilize Klarna Payments API

**First install the package.**

Composer require shasan-dev/laravel-klarna-payments

**After that publish the config file**

php artisan vendor:publish --provider="LaravelKlarna\\KlarnaPayments\\KlarnaPaymentsServiceProvider" --tag="klarna-payments-config"

**After that in your .env file add-**

KLARNA_USER_ID= your user id

KLARNA_PASSWORD= your password

KLARNA_TESTMODE= true or false

KLARNA_REGION= NA or OC or EU ( default EU)

**Make Controller**

php artisan make:controller KlarnaController

**Then, create 2 routes**

Route::get('payment/klarna-checkout', \[KlarnaController::class, 'payment'\])->name('klarna.checkout');

Route::get('payment/klarna-success', \[KlarnaController::class, 'paymentSuccess'\])->name('klarna.success');

**In controller**

use LaravelKlarna\\KlarnaPayments\\Facades\\KlarnaPayments;

public function payment(){

$data = \[

&nbsp; 'order_amount' => 20 \* 100,

&nbsp; 'order_lines' => \[

&nbsp; \[

&nbsp; 'name' => 'Running shoe',

&nbsp; 'quantity' => 1,

&nbsp; 'quantity_unit' => 'pcs',

&nbsp; 'total_amount' => 20 \* 100,

&nbsp; 'type' => 'physical',

&nbsp; 'unit_price' => 20 \* 100,

&nbsp; \]

&nbsp; \],

&nbsp; 'purchase_country' => 'US',

&nbsp; 'purchase_currency' => 'USD',

&nbsp; 'intent' => 'buy'

&nbsp; \];

&nbsp; $sessionId = KlarnaPayments::createSession($data)->json()\['session_id'\];

&nbsp; $data = array(

&nbsp; "success" => route('klarna.success') . "?sid={{session_id}}&order_id={{order_id}}",

&nbsp; "cancel" => route('home'),

&nbsp; "back" => route('home'),

&nbsp; "failure" => route('home') . "?sid={{session_id}}",

&nbsp; "error" => route('home') . "?sid={{session_id}}"

&nbsp; );

&nbsp; $redirectUrl = KlarnaPayments::createHppSession($sessionId, $data)->json()\['redirect_url'\];

&nbsp; return redirect()->to($redirectUrl);

}

public function paymentSuccess(Request $request){

$checkPayment = KlarnaPayments::checkPayment(Request::get('order_id'));

&nbsp; if ($checkPayment->json()\['status'\] == 'AUTHORIZED') {

&nbsp; echo 'Payment Success. Reference No. ' . $checkPayment->json()\['klarna_reference'\];

&nbsp; return;

&nbsp; } else {

&nbsp; echo 'Payment Not Success';

&nbsp; return;

&nbsp; }

}

**Now go to “**payment/klarna-checkout**” url .**

If you like the package don’t forget to star. Thanks.
