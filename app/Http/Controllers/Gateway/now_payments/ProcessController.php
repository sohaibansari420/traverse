<?php

namespace App\Http\Controllers\Gateway\now_payments;

use App\Models\Deposit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;

class ProcessController extends Controller
{
    /*
     * now_payments Pay Gateway
     */

    public static function process($deposit)
    {
        $nowpaymentsAcc = json_decode($deposit->gateway_currency()->gateway_parameter);

        $header = array(
            'x-api-key: ' . trim($nowpaymentsAcc->api_key),
            'Content-Type: application/json'
        );

        $data = Deposit::where('trx', $deposit->trx)->orderBy('id', 'DESC')->first();

        if($data->method_currency == 'BTC'){
             $response = json_decode(curlGetContent("https://api.nowpayments.io/v1/estimate?amount=$deposit->final_amo&currency_from=usd&currency_to=btc", $header));
             $amount = $response->estimated_amount;
        }
        else{
            $amount = $deposit->final_amo;
        }

        if ($data->btc_amo == 0 || $data->btc_wallet == "") {
            
            $secret = trim($nowpaymentsAcc->secret_code);
            $invoice_id = $data->trx;
            $callback_url = route('ipn.'.$deposit->gateway->alias);


            $param = array(
                'price_amount' => $amount,
                'price_currency' => 'usd',
                'pay_amount' => $amount,
                'pay_currency' => $data->method_currency,
                // 'ipn_callback_url' => 'https://nowpayments.io',
                'ipn_callback_url' => $callback_url,
                'order_id' => $deposit->trx,
                'order_description' => 'Plan Purchase',
                'ipn_secret'        => $secret,
            );

            $response = curlPostContentHeader("https://api.nowpayments.io/v1/payment", $param, $header);
            $response = json_decode($response);
            if (@$response->pay_address == '') {
                $send['error'] = true;
                $send['message'] = 'NOW PAYMENTS API HAVING ISSUE. PLEASE TRY LATER. ' . $response->message;
            } else {

                $sendto = $response->pay_address;
                $data['btc_wallet'] = $sendto;
                $data['btc_amo'] = $response->pay_amount;
                $data['try'] = $response->payment_id;
                $data->update();
            }
        }
        $DepositData = Deposit::where('trx', $deposit->trx)->orderBy('id', 'DESC')->first();
        $send['amount'] = $DepositData->btc_amo;
        $send['sendto'] = $DepositData->btc_wallet;
        $send['img'] = cryptoQR($DepositData->btc_wallet, $DepositData->btc_amo);
        $send['currency'] = $data->method_currency;
        $send['view'] = 'user.payment.crypto';
        return json_encode($send);
    }

    public function ipn()
    {   
        $payload = json_decode(file_get_contents("php://input"), true);

        if (!$payload || !isset($payload['payment_status'], $payload['order_id'])) {
            return; // Invalid request
        }

        $trx = $payload['order_id'];
        $paymentStatus = $payload['payment_status'];
        $payAddress = $payload['pay_address'];
        $payAmount = $payload['pay_amount'];
        $confirmations = $payload['confirmations'] ?? 0;
        $ipnSecret = $payload['ipn_secret'] ?? null;

        $track = $_GET['invoice_id'];
        $value_in_btc = $_GET['value'] / 100000000;

        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        $gateway = json_decode($data->gateway_currency()->gateway_parameter);
        $expectedSecret = trim($gateway->secret_code);

        // Load gateway config from the related deposit
        $nowpaymentsAcc = json_decode($data->gateway_currency()->gateway_parameter);
        $secret = trim($nowpaymentsAcc->secret_code);

        if (
            $paymentStatus === 'finished' &&
            $payAddress === $data->btc_wallet &&
            $ipnSecret === $expectedSecret &&
            $data->status == 0
        ) {
            PaymentController::userDataUpdate($data->trx);
        }
    }
}
