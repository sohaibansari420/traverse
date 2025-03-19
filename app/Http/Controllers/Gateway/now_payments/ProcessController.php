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

        // $header = array(
        //     'x-api-key: ' . trim($nowpaymentsAcc->api_key),
        //     'Content-Type: application/json'
        // );

        $data = Deposit::where('trx', $deposit->trx)->orderBy('id', 'DESC')->first();

        // if($data->method_currency == 'BTC'){
            //  $response = json_decode(curlGetContent("https://api.nowpayments.io/v1/estimate?amount=$deposit->final_amo&currency_from=usd&currency_to=btc", $header));
            //  $amount = $response->estimated_amount;
        // }
        // else{
            $amount = $deposit->final_amo;
        // }

        if ($data->btc_amo == 0 || $data->btc_wallet == "") {
            
            $secret = trim($nowpaymentsAcc->secret_code);
            $invoice_id = $data->trx;
            $callback_url = route('ipn.'.$deposit->gateway->alias) . "?invoice_id=" . $invoice_id . "&secret=" . $secret;


            $param = array(
                'price_amount' => $amount,
                'price_currency' => 'usd',
                'pay_amount' => $amount,
                'pay_currency' => $data->method_currency,
                'ipn_callback_url' => 'https://nowpayments.io',
                'order_id' => $deposit->trx,
                'order_description' => 'Plan Purchase'
            );

            // $response = curlPostContentHeader("https://api.nowpayments.io/v1/payment", $param, $header);
            // $response = json_decode($response);
            // if (@$response->pay_address == '') {
            //     $send['error'] = true;
            //     $send['message'] = 'NOW PAYMENTS API HAVING ISSUE. PLEASE TRY LATER. ' . $response->message;
            // } else {

                // $sendto = $response->pay_address;
                $data['btc_wallet'] = 'TQxu2vtKhQs2P4iSCKhfJhd4ojVspUkhqR';
                $data['btc_amo'] = $amount;
                // $data['try'] = $response->payment_id;
                $data->update();
            // }
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
        /*$track = $_GET['invoice_id'];
        $value_in_btc = $_GET['value'] / 100000000;
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        if ($data->btc_amo == $value_in_btc && $_GET['address'] == $data->btc_wallet && $_GET['secret'] == "ABIR" && $_GET['confirmations'] > 2 && $data->status == 0) {
            PaymentController::userDataUpdate($data->trx);
        }*/
    }
}
