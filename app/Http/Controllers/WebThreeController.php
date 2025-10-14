<?php

namespace App\Http\Controllers;

use App\Models\UserWallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Contract;
use kornrunner\Ethereum\Transaction;
use phpseclib\Math\BigInteger;
use Web3\Providers\HttpProvider;
use kornrunner\Keccak;
use kornrunner\Secp256k1;
use PayPal\Api\Amount;

class WebThreeController extends Controller
{
    private $rpcUrl;
    private $tokenAddress;
    private $fromAddress;
    private $privateKey;

    public function __construct()
    {
        $this->rpcUrl = env('RPC_URL');
        $this->tokenAddress = env('TOKEN_ADDRESS');
        $this->fromAddress = env('FROM_ADDRESS');
        $this->privateKey = trim(env('PRIVATE_KEY'));
    }

    public function WalletWEBWithdrawal(Request $request)
    {
        $recent = Withdrawal::where('user_id', auth()->id())
            ->where('wallet_id', $request->walletID)
            ->where('status', 1)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($recent) {
            return response()->json(['error' => 'You already have a pending withdrawal, please wait a moment.'], 429);
        }

        if (!ctype_xdigit($this->privateKey) || strlen($this->privateKey) !== 64) {
            throw new \Exception("Invalid private key format");
        }
        $to = $request->input('wallet');
        $amount = $request->input('amount');
        $chargeAmount = $amount * 0.10;
        $amount = $amount -  $chargeAmount;
        
        if ($amount >= 50) {
            return response()->json(['error' => 'Your Requested Amount is Smaller Than Minimum Amount.'], 400);
        }

        if (!$to || !$amount) {
            return response()->json(['error' => 'Wallet or amount missing'], 400);
        }

        $user = auth()->user();
        $user_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', $request->walletID)->firstOrFail();
        if ($amount > $user_wallet->balance && $amount < 20) {
             return response()->json(['error' => 'Your do not have Sufficient Balance For Withdraw.'], 400);
        }

        if (!ctype_xdigit($this->privateKey) || strlen($this->privateKey) !== 64) {
            return response()->json(['error' => 'Invalid private key format'], 400);
        }

        $provider = new HttpProvider($this->rpcUrl, 30);

        $web3 = new Web3($provider);

        $web3 = new Web3($this->rpcUrl);

        // ERC20 transfer function ABI
        $erc20Abi = '[{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transfer","outputs":[{"name":"","type":"bool"}],"type":"function"}]';
        $decimals = 18; // USDT decimals
        $value = bcmul((string)$amount, bcpow('10', (string)$decimals));
        $contract = new Contract($this->rpcUrl, $erc20Abi);
        
        $data = $contract->at($this->tokenAddress)->getData('transfer', $to, "0x" . (new BigInteger($value))->toHex());

        $nonce = null;
        $web3->eth->getTransactionCount($this->fromAddress, function ($err, $count) use (&$nonce) {
            if ($err) {
                echo $err->getMessage();
                return;
            }
            $nonce = $count;
        });

        if ($nonce === null) {
            return response()->json(['error' => 'Unable to get nonce'], 500);
        }

        $txData = [
            'nonce' => '0x' . $nonce->toHex(),
            'gasPrice' => '0x' . (new BigInteger('5000000000'))->toHex(), // 5 Gwei
            'gasLimit' => '0x' . (new BigInteger('60000'))->toHex(),
            'to' => (string)$this->tokenAddress,
            'value' => '0x0',
            'data' => $data ?? '0x',
            'chainId' => 56
        ];

        $transaction = new Transaction(
            $txData['nonce'],
            $txData['gasPrice'],
            hexdec($txData['gasLimit']),
            $txData['to'],
            hexdec($txData['value']),
            $txData['data'],
            $txData['chainId']
        );
        $signedTx = '0x' . $transaction->getRaw($this->privateKey);

        $result = null;
        $web3->eth->sendRawTransaction($signedTx, function ($err, $txHash) use (&$result , $request, $amount) {
            if ($err) {
                $result = ['error' => $err->getMessage()];
                return;
            }
            $this->withdrawStore($request->walletID , $amount , $txHash);
            $result = ['txHash' => $txHash];
        });
        
        return response()->json($result);
    }

    
    public function withdrawStore($id , $amount , $txHash)
    {
        $user = auth()->user();
        $user_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', $id)->firstOrFail();
        if ($amount > $user_wallet->balance) {
            $notify[] = ['error', 'Your do not have Sufficient Balance For Withdraw.'];
            return back()->withNotify($notify);
        }

        $charge = "10%";
        $afterCharge = $amount;
        $finalAmount = getAmount($amount);

        $withdraw = new Withdrawal();
        $withdraw->method_id = "0"; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->wallet_id = $id;
        $withdraw->country = $user->address->country;
        $withdraw->amount = getAmount($amount);
        $withdraw->currency = "$";
        $withdraw->rate = "1";
        $withdraw->charge = $charge;
        $withdraw->status = 1;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = $txHash;
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);

        updateWallet($user->id, $withdraw->trx, $withdraw->wallet_id, NULL, '-', getAmount($withdraw->amount), getAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via WEB3 WALLET' , getAmount($withdraw->charge), 'withdraw_approved', NULL,NULL);
        return "success";
    }
}
