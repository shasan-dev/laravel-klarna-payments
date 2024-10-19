<?php

namespace LaravelKlarna\KlarnaPayments;


use Illuminate\Support\Facades\Http;

class KlarnaPayments
{

    var $base_url;
    var $uid = "";
    var $pass = "";

    var $testMode;
    var $regionInit;
    public function __construct()
    {

        $userId = config()->get('klarna-payments.userid');
        $password = config()->get('klarna-payments.password');
        $region = config()->get('klarna-payments.region');
        $testmode = config()->get('klarna-payments.testmode');


        $this->testMode = true;

        if ($testmode != null) {
            $this->testMode = $testmode;
        }
        $this->regionInit = 'EU'; // NA or OC or EU

        if ($region != null) {
            $this->regionInit = $region;
        }

        $addedVariable = '';

        if ($this->regionInit == 'OC') {
            $addedVariable = '-oc';
        }
        if ($this->regionInit == 'NA') {
            $addedVariable = '-na';
        }


        if ($this->testMode == true) {
            $this->base_url = "https://api{$addedVariable}.playground.klarna.com";
        } else {
            $this->base_url = "https://api{$addedVariable}.klarna.com";
        }

        $this->uid = $userId;
        $this->pass = $password;
    }

    public function createSession(array $data)
    {
        $url = "{$this->base_url}/payments/v1/sessions";


        $response = Http::withBasicAuth($this->uid, $this->pass)->withHeaders([
            "Content-Type" => 'application/json',
        ])->post($url, $data);

        $response->throwUnlessStatus(200);

        return $response;
    }

    public function createHPPSession($sessionId, array $dataMerchant)
    {
        $url = "{$this->base_url}/hpp/v1/sessions";


        $data = array(
            "payment_session_url" => "{$this->base_url}/payments/v1/sessions/$sessionId",
            "merchant_urls" => $dataMerchant,
            "options" => array(
                "place_order_mode" => "PLACE_ORDER"
            )
        );
        $response = Http::withBasicAuth($this->uid, $this->pass)->withHeaders([
            "Content-Type" => 'application/json',
        ])->post($url, $data);

        $response->throwUnlessStatus(201);
        return $response;
    }

    public function checkPayment(string $order_id)
    {

        $url = "{$this->base_url}/ordermanagement/v1/orders/" . $order_id;
        $orderId = $order_id;

        $response = Http::withBasicAuth($this->uid, $this->pass)->withHeaders([
            "Content-Type" => 'application/json',
        ])->get($url);

        $response->throwUnlessStatus(200);

        return $response;
    }
}
