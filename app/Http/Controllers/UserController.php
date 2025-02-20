<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\BvLog;
use App\Models\Commission;
use App\Models\CronUpdate;
use App\Models\Deposit;
use App\Models\Epin;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\Rank;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserFamily;
use App\Models\UserWallet;
use App\Models\Wallet;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Image;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    public function home()
    {
        $general = GeneralSetting::first();
        
        // if(Auth::user()->check_stealthtradebot == NULL){
        //     $user = User::find(Auth::id());
        //     $updated_at = Carbon::now();
        //     $user->check_stealthtradebot = $updated_at;
        //     $user->save();
        // }

        if(Auth::user()->check_car == NULL){
            $user = User::find(Auth::id());
            $updated_at = Carbon::now();
            $user->check_car = $updated_at;
            $user->save();
        }

        $data['page_title']         = "Dashboard";
        $data['db_name']            = Config::get('database.default');
        $data['totalDeposit']       = Deposit::where('user_id', auth()->id())->where('status', 1)->sum('amount');
        $data['totalWithdraw']      = Withdrawal::where('user_id', auth()->id())->where('status', 1)->sum('amount');
        $data['totalBvCut']         = getAmount(Auth::user()->total_bv);
        $data['user_extras']        = UserExtra::where('user_id', Auth::user()->id)->first();
        $data['plans']              = PurchasedPlan::where(["user_id" => Auth::user()->id])->orderBy('id', 'desc')->get();
        $data['purchased_plans']    = PurchasedPlan::where(["user_id" => Auth::user()->id])->where('type','sponsor')->orderBy('id', 'desc')->get();
        $data['wallets']            = UserWallet::where(["user_id" => Auth::user()->id])->where('status', 1)->get();
        $data['commissions']        = Commission::where('status', 1)->get();
        $data['rank']               = Rank::where(["id" => Auth::user()->rank_id])->first();
        $data['next_rank']          = Rank::where(["id" => Auth::user()->rank_id + 1])->first();

        if(getUserLowerPlan(Auth::id())){
            $data['same_direct']    = UserFamily::selectRaw('user_id, COUNT(*) as user_count')
                                        ->whereRaw('user_id = ' . Auth::id() . ' and level = 1 and plan_id >= ' . getUserLowerPlan(Auth::id())->plan_id)
                                        ->where('created_at','>=', Carbon::parse(Auth::user()->check_fairy))
                                        ->groupBy('user_id')
                                        ->orderBy('user_count', 'desc')
                                        ->first();

            $now = Carbon::now();
            $check_fairy = new Carbon(Auth::user()->check_fairy);
            $rem_days = $data['commissions'][4]->commissionDetail[0]->days - $check_fairy->diffInDays($now);

            if (@$data['same_direct']->user_count >= $data['commissions'][4]->commissionDetail[0]->direct && $rem_days > 0) {

                cashbackCommission(Auth::user());
            }

            if ($rem_days < 0) {

                $user = Auth::user();
                $user->check_fairy = $now;
                $user->save();
            }

            // $direct_sales    = UserFamily::whereRaw('user_id = ' . Auth::id() . ' and level = 1 ')
            //                             ->where('created_at','>=',Carbon::parse(Auth::user()->check_car)->subDays(1))
            //                             ->get();
            // $total_direct_sale = 0;
            // foreach($direct_sales as $direct_sale){
            //     if ($direct_sale->plan_id != 0) {
            //         $total_direct_sale += Plan::where('id', $direct_sale->plan_id)->firstOrFail()->price;
            //     }
            // }

            // $data['direct_sale'] =  $total_direct_sale;

            // $car_days = $data['commissions'][6]->commissionDetail[0]->days;
            // $car_days_date = Carbon::parse(Auth::user()->check_car)->addDays($car_days);
            // $now = Carbon::now();

            // if($car_days_date->lte($now)){
            //     carShare(Auth::id(), $total_direct_sale,$car_days_date);
            // }
        }
        
        //updateRankStatus(Auth::id());
        
        return view($this->activeTemplate . 'user.dashboard', $data);
    }

    function stormPlan(Request $request)
    {
        $this->validate($request, [
            'wallet_id' => 'required'
        ]);
        
        $user = User::find(Auth::id());

        if($user->storm_plan ){
            $notify[] = ['error', 'Already Purchased'];
            return back()->withNotify($notify);
        }

        if(!$user->plan_purchased){
            $notify[] = ['error', 'Buy Master Plan First'];
            return back()->withNotify($notify);
        }

        $storm_plan = Plan::where('id', 0)->first();

        $user_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', $request->wallet_id)->firstOrFail();
        $amount = $storm_plan->price;

        if ($user_wallet->balance >= $amount) {

            $trx = getTrx();
            $notify[] = updateWallet($user->id, $trx, $user_wallet->wallet_id, NULL, '-', getAmount($amount), 'Subscribed To ' . $storm_plan->name, 0, 'storm_plan', NULL,'');

            $user->storm_plan = 1;
            $user->save();

            CronUpdate::create([
                'user_id' => $user->id,
                'type' => 'storm_plan',
                'amount' => $amount,
                'details' => $user->username . ' Subscribed To ' . $storm_plan->name,
                'status' => 0,
            ]);

            $flushed_storms = Transaction::where(['wallet_id' => 6, 'user_id' => Auth::id(), 'commission_id' => 9, 'trx_type' => '+'])
                    ->where('created_at','>=',Carbon::now()->subDay()->toDateTimeString())
                    ->get();
        
            foreach($flushed_storms as $data){
                $trx = getUserHigherPlan($user->id)->trx;
            $details = 'Return Flushed '. getCommissionName($data->commission_id);
                updateWallet($user->id, getTrx(), 6 , $data->commission_id, '-', getAmount($data->amount), $details, 0, str_replace(' ', '_', getCommissionName($data->commission_id)), $trx,''); 
                $commission = getCommission($data->commission_id);
                $commission_detail = $commission->commissionDetail[0];
                updateCommissionWithLimit($user->id, $data->amount, $commission->wallet_id, $data->commission_id, $details, $commission_detail->commission_limit, $trx);
            }
            

            $notify[] = ['success', 'Storm Plan Subscribed Successfully.'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Insufficient Balance.'];
            return back()->withNotify($notify);

        }
    }

    public function profile()
    {
        $data['page_title'] = "Profile Setting";
        $data['user'] = Auth::user();
        return view($this->activeTemplate. 'user.profile-setting', $data);
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'mobile' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ],[
            'firstname.required'=>'First Name Field is required',
            'lastname.required'=>'Last Name Field is required'
        ]);


        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        
        $in['mobile'] = $request->mobile;
        
        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'city' => $request->city,
        ];


        $user = Auth::user();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $user->username . '.jpg';
            $location = 'assets/images/user/profile/' . $filename;
            $in['image'] = $filename;

            $path = './assets/images/user/profile/';
            $link = $path . $user->image;
            if (file_exists($link)) {
                //@unlink($link);
            }
            $size = imagePath()['profile']['user']['size'];
            $image = Image::make($image);
            $size = explode('x', strtolower($size));
            $image->resize($size[0], $size[1]);
            $image->save($location);
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile Updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $data['page_title'] = "Change Password";
        return view($this->activeTemplate . 'user.password', $data);
    }

    public function submitPassword(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed'
        ]);
        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password Changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'Current password not match.'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $page_title = 'Deposit History';
        $empty_message = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('page_title', 'empty_message', 'logs'));
    }

    /*
     * Withdraw Operation
     */

    public function withdrawMoney(Request $request)
    {
        $data['withdrawMethod'] = WithdrawMethod::whereStatus(1)->get();
        $data['wallet_id'] = $request->walletID;
        $data['page_title'] = "Withdraw Money";
        $withdraw_status = Wallet::findOrFail($data['wallet_id'])->withdraw;
        $data['wallet_balance'] = UserWallet::where('user_id', Auth::id())->where('wallet_id', $data['wallet_id'])->firstOrFail()->balance;
        if(!$withdraw_status){
            $notify[] = ['error', 'Request Error....'];
            return back()->withNotify($notify);
        }
        if($data['wallet_id'] == 9){
            $balance = UserWallet::where('user_id', Auth::id())->where('wallet_id', 9)->firstOrFail()->balance;
            if($balance > 0){
                $data['created_at'] = Transaction::where('user_id', Auth::id())->where('wallet_id', 9)->firstOrFail()->created_at;
                $days = 365;
                $join_date = \Carbon\Carbon::parse($data['created_at']);
                $now = \Carbon\Carbon::now();
                $diff = $join_date->diffInSeconds($now);
                $data['remaining'] = $days * 86400 - $diff;
            }
        }

        return view(activeTemplate() . 'user.withdraw.methods', $data);
    }

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'wallet_id' => 'required',
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        $user_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', $request->wallet_id)->firstOrFail();
        
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your Requested Amount is Smaller Than Minimum Amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your Requested Amount is Larger Than Maximum Amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $user_wallet->balance) {
            $notify[] = ['error', 'Your do not have Sufficient Balance For Withdraw.'];
            return back()->withNotify($notify);
        }

        if ($user->verify != 2) {
            $notify[] = ['error', 'KYC Verification is needed For Withdraw.'];
            return redirect()->route('user.kyc.verify')->withNotify($notify);
        }

        if($request->wallet_id == 9){
            $created_at = Transaction::where('user_id', Auth::id())->where('wallet_id', 9)->firstOrFail()->created_at;
            $days = 365;
            $join_date = \Carbon\Carbon::parse($created_at);
            $now = \Carbon\Carbon::now();
            $diff = $join_date->diffInSeconds($now);
            $remaining = $days * 86400 - $diff;
            if($remaining > 0){
                $notify[] = ['error', 'Passive Wallet Is Not Activated'];
                return back()->withNotify($notify);
            }
        }

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = getAmount($afterCharge * $method->rate);

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->wallet_id = $request->wallet_id;
        $withdraw->country = $user->address->country;
        $withdraw->amount = getAmount($request->amount);
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $data['withdraw'] = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->latest()->firstOrFail();
        $data['user_wallet'] = UserWallet::where('user_id', $data['withdraw']->user_id)->where('wallet_id', $data['withdraw']->wallet_id)->firstOrFail();
        $data['page_title'] = "Withdraw Preview";
        return view($this->activeTemplate . 'user.withdraw.preview', $data);
    }


    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->latest()->firstOrFail();
        $user_wallet = UserWallet::where('user_id', $withdraw->user_id)->where('wallet_id', $withdraw->wallet_id)->firstOrFail();

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);
        $user = auth()->user();

        if (getAmount($withdraw->amount) > $user_wallet->balance) {
            $notify[] = ['error', 'Your Request Amount is Larger Then Your Current Balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['withdraw']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }

        $notify[] = updateWallet($user->id, $withdraw->trx, $withdraw->wallet_id, NULL, '-', getAmount($withdraw->amount), getAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name, getAmount($withdraw->charge), 'withdraw_request', NULL,NULL);
        
        $withdraw->status = 2;
        $withdraw->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New withdraw request from '.$user->username;
        $adminNotification->click_url = route('admin.withdraw.details',$withdraw->id);
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => getAmount($withdraw->final_amount),
            'amount' => getAmount($withdraw->amount),
            'charge' => getAmount($withdraw->charge),
            'currency' => $general->cur_text,
            'rate' => getAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => getAmount($user->balance),
            'delay' => $withdraw->method->delay
        ]);

        $notify[] = ['success', 'Withdraw Request Successfully Send'];
        return redirect()->route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog()
    {
        $data['page_title'] = "Withdraw Log";
        $data['withdraws'] = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->latest()->paginate(getPaginate());
        $data['empty_message'] = "No Data Found!";
        return view($this->activeTemplate.'user.withdraw.log', $data);
    }



    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $secret);
        $prevcode = $user->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Security';
        return view($this->activeTemplate.'user.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);


            $notify[] = ['success', 'Google Authenticator Enabled Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $ga = new GoogleAuthenticator();

        $secret = $user->tsc;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {

            $user->tsc = null;
            $user->ts = 0;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->with($notify);
        }
    }

    function kycVerification()
    {
        $page_title = 'KYC Verification';
        return view($this->activeTemplate . '.user.kycVerification', compact('page_title'));
    }


    public function submitKYC(Request $request)
    {
        $this->validate($request, [
            'front' => 'mimes:png,jpg,jpeg,svg,gif',
            'selfie' => 'mimes:png,jpg,jpeg,svg,gif',
            'back' => 'mimes:png,jpg,jpeg,svg,gif',
            // 'btc_wallet' => 'required',
            'trc20_wallet' => 'required',
        ]);
        
        $db_name = Config::get('database.default');

        $in['verify'] = 1;
        $in['btc_wallet'] = $request->btc_wallet ?? '';
        $in['trc20_wallet'] = $request->trc20_wallet;

        $user = Auth::user();

        if ($request->hasFile('front')) {
            $image = $request->file('front');
            $filename = $user->id . $user->username . rand() . 'front.' . $image->getClientOriginalExtension();
            if($db_name == "mysql2"){
                $location = 'myamano/assets/images/user/kyc/' . $filename;
            }elseif($db_name == "mysql3"){
                $location = 'amano3/assets/images/user/kyc/' . $filename;
            }elseif($db_name == "mysql4"){
                $location = 'amano4/assets/images/user/kyc/' . $filename;
            }else{
                $location = 'assets/images/user/kyc/' . $filename;
            }
            
            $in['front'] = $filename;
            $image = Image::make($image);
            $image->save($location);
        }

        if ($request->hasFile('back')) {
            $image = $request->file('back');
            $filename = $user->id . $user->username . rand() . 'back.' . $image->getClientOriginalExtension();
            if($db_name == "mysql2"){
                $location = 'myamano/assets/images/user/kyc/' . $filename;
            }elseif($db_name == "mysql3"){
                $location = 'amano3/assets/images/user/kyc/' . $filename;
            }elseif($db_name == "mysql4"){
                $location = 'amano4/assets/images/user/kyc/' . $filename;
            }else{
                $location = 'assets/images/user/kyc/' . $filename;
            }
            $in['back'] = $filename;
            $image = Image::make($image);
            $image->save($location);
        }

        if ($request->hasFile('selfie')) {
            $image = $request->file('selfie');
            $filename = $user->id . $user->username . rand() . 'selfie.' . $image->getClientOriginalExtension();
            if($db_name == "mysql2"){
                $location = 'myamano/assets/images/user/kyc/' . $filename;
            }elseif($db_name == "mysql3"){
                $location = 'amano3/assets/images/user/kyc/' . $filename;
            }elseif($db_name == "mysql4"){
                $location = 'amano4/assets/images/user/kyc/' . $filename;
            }else{
                $location = 'assets/images/user/kyc/' . $filename;
            }
            $in['selfie'] = $filename;
            $image = Image::make($image);
            $image->save($location);
        }

        $user->fill($in)->save();
        
        notify($user, 'kyc_request', [
            
        ]);
        
        $notify[] = ['success', 'KYC Updated successfully.'];
        return back()->withNotify($notify);
    }
    
    public function eWallet()
    {
        $wallets = UserWallet::where(["user_id" => Auth::user()->id])->where('status', 1)->get();
        $plans = Plan::where('status', 1)->where('id', '<=', 7)->get();
        $epins = Epin::where('created_by', Auth::user()->id)->get();
        $page_title = 'E-Wallet';
        return view($this->activeTemplate . 'user.ewallet', compact('plans', 'wallets', 'epins', 'page_title'));
    }

    public function ranks()
    {
        $ranks = Rank::all();
        $rank  = Rank::where(["id" => Auth::user()->rank_id])->first();
        $next_rank = Rank::where(["id" => Auth::user()->rank_id + 1])->first();
        $page_title = 'Ranks';
        return view($this->activeTemplate . 'user.ranks', compact('ranks', 'rank', 'next_rank', 'page_title'));
    }

    function buyPlanUser(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required',
            'cash' => 'required',
            'transaction' => 'required',
        ]);

        $trx = getTrx();
        $user = User::find(Auth::id());
        $plan = Plan::where('id', $request->plan)->where('status', 1)->firstOrFail();
        $c_amount = UserWallet::where('user_id', $user->id)->where('wallet_id', 1)->firstOrFail()->balance;
        $t_amount = UserWallet::where('user_id', $user->id)->where('wallet_id', 3)->firstOrFail()->balance;
        $cash_amount = $request->cash;
        $transaction_amount = $request->transaction;
        
        if ($user->verify != 2) {
            $notify[] = ['error', 'KYC Verification is needed For Epin.'];
            return redirect()->route('user.kyc.verify')->withNotify($notify);
        }

        if($cash_amount > $c_amount){
            $notify[] = ['error', 'Insufficient Cash Balance.'];
            return back()->withNotify($notify);
        }

        if($transaction_amount > $t_amount){
            $notify[] = ['error', 'Insufficient Transaction Balance.'];
            return back()->withNotify($notify);
        }

        $details = $user->username . ' Purchased E-Pin';
        
        $notify[] = updateWallet($user->id, $trx, 1, NULL, '-', getAmount($cash_amount), $details, 0, 'purchased_epin', NULL,NULL);
        
        if($transaction_amount != 0){
            $notify[] = updateWallet($user->id, $trx, 3, NULL, '-', getAmount($transaction_amount), $details, 0, 'purchased_epin', NULL,NULL);
        }

        $epin = 'epin_'.Str::random(12);
        
        $epins = new Epin();
        $epins->created_by = $user->id;
        $epins->amount = $plan->price;
        $epins->epin = $epin;
        $epins->save();
        

        $notify[] = ['success', 'Plan Buy Successfully.'];
        return back()->withNotify($notify);
    }


    function indexTransfer()
    {
        $wallets = UserWallet::where(["user_id" => Auth::user()->id])->where('status', 1)->get();
        $page_title = 'Balance Transfer';
        return view($this->activeTemplate . '.user.balanceTransfer', compact('page_title', 'wallets'));
    }
    
    function balanceTransfer(Request $request)
    {
        $this->validate($request, [
            'wallet_id' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);
        $gnl = GeneralSetting::first();
        $user = User::find(Auth::id());
        $user_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', $request->wallet_id)->firstOrFail();
        $transfer_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', 3)->firstOrFail();
        $charge = ($request->amount * $gnl->bal_trans_fixed_charge) / 100;
        $amount = $request->amount;
        $total = $amount - $charge;

        if ($user_wallet->balance >= $amount) {

            $trx = getTrx();

            $notify[] = updateWallet($user->id, $trx, $user_wallet->wallet_id, NULL, '-', getAmount($amount), 'Balance Transferred To ' . $transfer_wallet->wallet->name, 0, 'transfer_balance', NULL,NULL);

            $notify[] = updateWallet($user->id, $trx, $transfer_wallet->wallet_id, NULL, '+', getAmount($total), 'Balance Received From ' . $user_wallet->wallet->name, $charge, 'receive_balance', NULL,NULL);

            $notify[] = ['success', 'Balance Transferred Successfully.'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Insufficient Balance.'];
            return back()->withNotify($notify);

        }
    }

    public function getChargeAjax(Request $request)
    {
        $general = GeneralSetting::first();
        $amount = floatval($request->amount);

        $per = getAmount($general->bal_trans_fixed_charge);
        $charge = ( $amount *  $per )/100;
        
        $total = $amount - $charge;

        if ($amount == '')
        {
            return "<span style='color: red'>Invalid Amount</span>";
        }else{
            return "<span style='color: red'>With $per % Charges, You will receive $general->cur_text $total</span>";
        }

    }


    function searchUser(Request $request)
    {
        $trans_user = User::where('username', $request->username)->orwhere('email', $request->username)->count();
        if ($trans_user == 1) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }

    public function userLoginHistory()
    {
        $page_title = 'User Login History';
        $empty_message = 'No users login found.';
        $login_logs = auth()->user()->login_logs()->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.logins', compact('page_title', 'empty_message', 'login_logs'));
    }



}
