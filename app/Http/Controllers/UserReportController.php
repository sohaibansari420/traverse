<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function bvBonusLog(Request $request)
    {

        $search = $request->search;
        if ($search) {
            $data['page_title'] = "Matching Bonus search : " . $search;
            $data['transactions'] = auth()->user()->transactions()->where('remark', 'matching_bonus')->where('trx', 'like', "%$search%")->latest()->paginate(getPaginate());
        } else {
            $data['page_title'] = 'Matching Bonus';
            $data['transactions'] = auth()->user()->transactions()->where('remark', 'matching_bonus')->latest()->paginate(getPaginate());
        }
        $data['search'] = $search;

        $data['empty_message'] = 'No data found.';
        return view($this->activeTemplate . 'user.transactions', $data);

    }

    public function investLog(Request $request)
    {

        $search = $request->search;
        if ($search) {
            $data['page_title'] = "Invest search : " . $search;
            $data['transactions'] = auth()->user()->transactions()->where('remark', 'purchased_plan')->where('trx', 'like', "%$search%")->latest()->paginate(getPaginate());
        } else {
            $data['page_title'] = 'Packages';
            $data['transactions'] = auth()->user()->transactions()->where('remark', 'purchased_plan')->latest()->paginate(getPaginate());
        }
        $data['search'] = $search;

        $data['empty_message'] = 'No data found.';
        return view($this->activeTemplate . 'user.transactions', $data);

    }

    public function transactions(Request $request)
    {

        $search = $request->search;
        if ($search) {
            $data['page_title'] = "Transaction search : " . $search;
            $data['transactions'] = auth()->user()->transactions()->where('trx', 'like', "%$search%")->latest()->paginate(getPaginate());
        } else {
            $data['page_title'] = 'Transactions';
            $data['transactions'] = auth()->user()->transactions()->latest()->paginate(getPaginate());
        }
        $data['search'] = $search;
        $data['empty_message'] = 'No transactions.';
        return view($this->activeTemplate . 'user.transactions', $data);

    }

    public function depositHistory(Request $request)
    {

        $search = $request->search;

        if ($search) {
            $data['page_title'] = "Deposit search : " . $search;
            $data['logs'] = auth()->user()->deposits()->where('trx', 'like', "%$search%")->with(['gateway'])->latest()->paginate(getPaginate());
        } else {
            $data['page_title'] = 'Deposits';
            $data['logs'] = auth()->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        }
        $data['search'] = $search;
        $data['empty_message'] = 'No history found.';


        return view($this->activeTemplate . 'user.deposit_history', $data);
    }

    public function withdrawLog(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $data['page_title'] = "Withdraw search : " . $search;
            $data['withdraws'] = auth()->user()->withdrawals()->where('trx', 'like', "%$search%")->with('method')->latest()->paginate(getPaginate());
        } else {
            $data['page_title'] = "Withdrawals";
            $data['withdraws'] = auth()->user()->withdrawals()->with('method')->latest()->paginate(getPaginate());
        }
        $data['search'] = $search;
        $data['empty_message'] = "No Data Found!";
        return view($this->activeTemplate . 'user.withdraw.log', $data);
    }

    public function commissions(Request $request)
    {
        $search = $request->search;
        if ($search){
            if ($request->commissionID)
            {
                $commission = Commission::where('id', $request->commissionID)->first();
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - ' . $commission->name . ' Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('commission_id', $commission->id)->where('trx', 'like', "%$search%")->with('user')->latest()->paginate(getPaginate());
                
            }else {
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - Commission Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('commission_id', '!=', NULL)->where('trx', 'like', "%$search%")->with('user')->latest()->paginate(getPaginate());
            }
        } 
        else{
            if ($request->commissionID)
            {
                $commission = Commission::where('id', $request->commissionID)->first();
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - ' . $commission->name . ' Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('commission_id', $commission->id)->with('user')->latest()->paginate(getPaginate());
                
            }else {
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - Commission Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('commission_id', '!=', NULL)->with('user')->latest()->paginate(getPaginate());
            }
        }

        $data['search'] = $search;

        $data['empty_message'] = 'No data found.';
        return view($this->activeTemplate . 'user.transactions', $data);

    }

    public function wallets(Request $request)
    {
        $search = $request->search;
        if ($search){
            if ($request->walletID)
            {
                $wallet = Wallet::where('id', $request->walletID)->first();
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - ' . $wallet->name . ' Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('wallet_id', $wallet->id)->where('trx', 'like', "%$search%")->with('user')->latest()->paginate(getPaginate());
                
            }else {
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - Wallet Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('wallet_id', '!=', NULL)->where('trx', 'like', "%$search%")->with('user')->latest()->paginate(getPaginate());
            }
        } 
        else{
            if ($request->walletID)
            {
                $wallet = Wallet::where('id', $request->walletID)->first();
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - ' . $wallet->name . ' Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('wallet_id', $wallet->id)->with('user')->latest()->paginate(getPaginate());
                
            }else {
                $user = User::findOrFail(Auth::id());
                $data['page_title'] = $user->username . ' - Wallet Logs';
                $data['transactions'] = Transaction::where('user_id', $user->id)->where('wallet_id', '!=', NULL)->with('user')->latest()->paginate(getPaginate());
            }
        }

        $data['search'] = $search;

        $data['empty_message'] = 'No data found.';
        return view($this->activeTemplate . 'user.transactions', $data);

    }

}
