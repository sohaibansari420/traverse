<?php
namespace App\Http\Controllers\Admin;

use App\Models\Deposit;
use App\Models\BvLog;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CronUpdate;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\Rank;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserLogin;
use App\Models\UserWallet;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Founder;


class ManageUsersController extends Controller
{
    public function allUsers()
    {
        $page_title = 'Manage Users';
        $data_type = "all";
        return view('admin.users.list', compact('page_title', 'data_type'));
    }

    public function activeUsers()
    {
        $page_title = 'Manage Active Users';
        $data_type = "active";
        return view('admin.users.list', compact('page_title', 'data_type'));
    }

    public function showKYC()
    {
        $page_title = 'Manage Users KYC';
        $data_type = "kyc";
        return view('admin.users.list', compact('page_title', 'data_type'));
    }

    public function bannedUsers()
    {
        $page_title = 'Banned Users';
        $data_type = "banned";
        return view('admin.users.list', compact('page_title', 'data_type'));
    }

    public function emailUnverifiedUsers()
    {
        $page_title = 'Email Unverified Users';
        $data_type = "emailUnverified";
        return view('admin.users.list', compact('page_title', 'data_type'));
    }
    public function emailVerifiedUsers()
    {
        $page_title = 'Email Verified Users';
        $empty_message = 'No email verified user found';
        $users = User::emailVerified()->latest()->paginate(getPaginate());
        return view('admin.users.list', compact('page_title', 'empty_message', 'users'));
    }

    public function smsUnverifiedUsers()
    {
        $page_title = 'SMS Unverified Users';
        $empty_message = 'No sms unverified user found';
        $users = User::smsUnverified()->latest()->paginate(getPaginate());
        return view('admin.users.list', compact('page_title', 'empty_message', 'users'));
    }
    public function smsVerifiedUsers()
    {
        $page_title = 'SMS Verified Users';
        $empty_message = 'No sms verified user found';
        $users = User::smsVerified()->latest()->paginate(getPaginate());
        return view('admin.users.list', compact('page_title', 'empty_message', 'users'));
    }

    public function login($id){
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function rankAchievers()
    {
        $page_title = 'Rank Achievers';
        return view('admin.users.rankachievers', compact('page_title'));
    }

    public function usersData($id, Request $request)
    {
        
        $columns = array( 
                            0 =>'id', 
                            1 =>'username',
                            2=> 'firstname',
                            3=> 'lastname',
                            3=> 'email',
                            4=> 'mobile',
                            5=> 'created_at',
                            6=> 'id',
                            7=> 'status',
                        );
                        
        if($id == "all"){
            $totalData = User::latest()->count();
        }elseif($id == "active"){
            $totalData = User::active()->count();
        }elseif($id == "banned"){
            $totalData = User::banned()->count();
        }elseif($id == "kyc"){
            $totalData = User::where('verify', 1)->count();
        }elseif($id == "emailUnverified"){
            $totalData = User::emailUnverified()->count();
        }
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        { 
            if($id == "all"){
                $records = User::offset($start)
                         ->limit($limit)
                         ->with('userWallet')
                         ->orderBy($order,$dir)
                         ->get();
            }elseif($id == "active"){
                $records = User::active()
                         ->offset($start)
                         ->limit($limit)
                         ->with('userWallet')
                         ->orderBy($order,$dir)
                         ->get();
            }elseif($id == "banned"){
                $records = User::banned()
                         ->offset($start)
                         ->limit($limit)
                         ->with('userWallet')
                         ->orderBy($order,$dir)
                         ->get();
            }elseif($id == "kyc"){
                $records = User::where('verify', 1)
                         ->offset($start)
                         ->limit($limit)
                         ->with('userWallet')
                         ->orderBy('submission_date', 'desc')
                         ->get();
            }elseif($id == "emailUnverified"){
                $records = User::emailUnverified()
                         ->offset($start)
                         ->limit($limit)
                         ->with('userWallet')
                         ->orderBy($order,$dir)
                         ->get();
            }           
        }
        else {
            $search = $request->input('search.value'); 

            if($id == "all"){
                $records =  User::where('id','LIKE',"{$search}%")
                            ->orWhere('username', 'LIKE',"{$search}%")
                            ->orWhere('email', 'like', "$search%")
                            ->orWhere('mobile', 'like', "$search%")
                            ->orWhere('firstname', 'like', "$search%")
                            ->orWhere('lastname', 'like', "$search%")
                            ->orWhere('created_at', 'like', "$search%")
                            ->offset($start)
                            ->limit($limit)
                            ->with('userWallet')
                            ->orderBy($order,$dir)
                            ->get();

                $totalFiltered = User::where('id','LIKE',"{$search}%")
                                ->orWhere('username', 'LIKE',"{$search}%")
                                ->orWhere('email', 'like', "$search%")
                                ->orWhere('mobile', 'like', "$search%")
                                ->orWhere('firstname', 'like', "$search%")
                                ->orWhere('lastname', 'like', "$search%")
                                ->orWhere('created_at', 'like', "$search%")
                                ->with('userWallet')
                                ->count();
            }elseif($id == "active"){
                $records =  User::active()
                            ->where('id','LIKE',"%{$search}%")
                            ->orWhere('username', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%")
                            ->orWhere('firstname', 'like', "%$search%")
                            ->orWhere('lastname', 'like', "%$search%")
                            ->orWhere('created_at', 'like', "%$search%")
                            ->offset($start)
                            ->limit($limit)
                            ->with('userWallet')
                            ->orderBy($order,$dir)
                            ->get();

                $totalFiltered = User::active()
                                ->where('id','LIKE',"%{$search}%")
                                ->orWhere('username', 'LIKE',"%{$search}%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('mobile', 'like', "%$search%")
                                ->orWhere('firstname', 'like', "%$search%")
                                ->orWhere('lastname', 'like', "%$search%")
                                ->orWhere('created_at', 'like', "%$search%")
                                ->with('userWallet')
                                ->count();
            }elseif($id == "banned"){
                $records =  User::banned()
                            ->where('id','LIKE',"%{$search}%")
                            ->orWhere('username', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%")
                            ->orWhere('firstname', 'like', "%$search%")
                            ->orWhere('lastname', 'like', "%$search%")
                            ->orWhere('created_at', 'like', "%$search%")
                            ->offset($start)
                            ->limit($limit)
                            ->with('userWallet')
                            ->orderBy($order,$dir)
                            ->get();

                $totalFiltered = User::banned()
                                ->where('id','LIKE',"%{$search}%")
                                ->orWhere('username', 'LIKE',"%{$search}%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('mobile', 'like', "%$search%")
                                ->orWhere('firstname', 'like', "%$search%")
                                ->orWhere('lastname', 'like', "%$search%")
                                ->orWhere('created_at', 'like', "%$search%")
                                ->with('userWallet')
                                ->count();
            }elseif($id == "kyc"){
                $records =  User::where('verify', 1)
                            ->where('id','LIKE',"%{$search}%")
                            ->orWhere('username', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%")
                            ->orWhere('firstname', 'like', "%$search%")
                            ->orWhere('lastname', 'like', "%$search%")
                            ->orWhere('created_at', 'like', "%$search%")
                            ->offset($start)
                            ->limit($limit)
                            ->with('userWallet')
                            ->orderBy('submission_date', 'desc')
                            ->get();

                $totalFiltered = User::where('verify', 1)
                                ->where('id','LIKE',"%{$search}%")
                                ->orWhere('username', 'LIKE',"%{$search}%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('mobile', 'like', "%$search%")
                                ->orWhere('firstname', 'like', "%$search%")
                                ->orWhere('lastname', 'like', "%$search%")
                                ->orWhere('created_at', 'like', "%$search%")
                                ->with('userWallet')
                                ->count();
            }elseif($id == "emailUnverified"){
                $records =  User::emailUnverified()
                            ->where('id','LIKE',"%{$search}%")
                            ->orWhere('username', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%")
                            ->orWhere('firstname', 'like', "%$search%")
                            ->orWhere('lastname', 'like', "%$search%")
                            ->orWhere('created_at', 'like', "%$search%")
                            ->offset($start)
                            ->limit($limit)
                            ->with('userWallet')
                            ->orderBy($order,$dir)
                            ->get();

                $totalFiltered = User::emailUnverified()
                                ->where('id','LIKE',"%{$search}%")
                                ->orWhere('username', 'LIKE',"%{$search}%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('mobile', 'like', "%$search%")
                                ->orWhere('firstname', 'like', "%$search%")
                                ->orWhere('lastname', 'like', "%$search%")
                                ->orWhere('created_at', 'like', "%$search%")
                                ->with('userWallet')
                                ->count();
            }
            
        }

        $data = array();
        if(!empty($records))
        {
            foreach ($records as $record)
            {
                $details =  route('admin.users.detail',$record->id);
                $general = GeneralSetting::first();

                $nestedData['id'] = $record->id;
                $nestedData['username'] = $record->username;
                $nestedData['firstname'] = $record->firstname;
                $nestedData['lastname'] = $record->lastname;
                $nestedData['email'] = $record->email;
                $nestedData['mobile'] = $record->mobile;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($record->created_at));
                $nestedData['action'] = "<a href='{$details}' class='icon-btn mr-3' data-toggle='tooltip' data-original-title='Details')'><i class='las la-desktop text--shadow'></i></a>";
                $nestedData['action'] .= $record->status ? "<button class='btn btn-danger js-enable-disable-user' data-id='$record->id'>Disable</button>" : "<button class='btn btn-success js-enable-disable-user' data-id='$record->id'>Enable</button>";
                
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
    }

    public function updateRoi($id, $option)
    {
        $user = User::find($id);
        $user->is_roi = $option;
        $user->save();

        $notify[] = ['success', 'User roi has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $users = User::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->orWhere('firstname', 'like', "%$search%")
                ->orWhere('lastname', 'like', "%$search%");
        });
        $page_title = '';
        switch ($scope) {
            case 'active':
                $page_title .= 'Active ';
                $users = $users->where('status', 1);
                break;
            case 'banned':
                $page_title .= 'Banned';
                $users = $users->where('status', 0);
                break;
            case 'emailUnverified':
                $page_title .= 'Email Unerified ';
                $users = $users->where('ev', 0);
                break;
            case 'smsUnverified':
                $page_title .= 'SMS Unverified ';
                $users = $users->where('sv', 0);
                break;
        }
        $users = $users->paginate(getPaginate());
        $page_title .= 'User Search - ' . $search;
        $empty_message = 'No search result found';
        return view('admin.users.list', compact('page_title', 'search', 'scope', 'empty_message', 'users'));
    }


    public function detail($id)
    {
        $page_title         = 'User Detail';
        $user               = User::where('id', $id)->with('userExtra')->first();
        $ref_id             = User::find($user->ref_id);
        $totalDeposit       = Deposit::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalWithdraw      = Withdrawal::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalTransaction   = Transaction::where('user_id',$user->id)->count();
        $rank               = Rank::where('id', $user->rank_id)->first()->name;
        $user_extras        = UserExtra::where('user_id', $user->id)->first();
        $user_wallets       = UserWallet::where('user_id', $user->id)->get();
        $commissions        = Commission::where('status', 1)->get();
        return view('admin.users.detail', compact('page_title','ref_id','user','totalDeposit',
            'totalWithdraw','totalTransaction',  'rank', 'user_extras', 'user_wallets', 'commissions'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'firstname' => 'required|max:60',
            'lastname' => 'required|max:60',
            'email' => 'required|email|max:160|unique:users,email,' . $user->id,
        ]);

        if ($request->email != $user->email && User::whereEmail($request->email)->whereId('!=', $user->id)->count() > 0) {
            $notify[] = ['error', 'Email already exists.'];
            return back()->withNotify($notify);
        }
        if ($request->mobile != $user->mobile && User::where('mobile', $request->mobile)->whereId('!=', $user->id)->count() > 0) {
            $notify[] = ['error', 'Phone number already exists.'];
            return back()->withNotify($notify);
        }

        $user->mobile = $request->mobile;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->btc_wallet = $request->btc_wallet;
        $user->trc20_wallet = $request->trc20_wallet;
        $user->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => $request->country,
                        ];
        $user->status = $request->status ? 1 : 0;
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->ts = $request->ts ? 1 : 0;
        $user->tv = $request->tv ? 1 : 0;
        $user->save();

        $notify[] = ['success', 'User detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function verifyUpdate(Request $request, $id)
    {
        $user = User::find($id);

        if ($request->verify == 2) {
            User::whereId($id)->update([
                'verify' => $request->verify,
            ]);

            $message = $request->message;
            
            notify($user, 'kyc_approved', [
                'details' => $message
            ]);

        } else {
            User::whereId($id)->update([
                'verify' => $request->verify,
            ]);

            $message = $request->message2;
            
            notify($user, 'kyc_rejected', [
                'details' => $message
            ]);
        }


        $notify[] = ['success', 'Successfully Done'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|gt:0']);

        $amount = getAmount($request->amount);
        $general = GeneralSetting::first();
        $trx = getTrx();

        if ($request->act) {

            $notify[] = updateWallet($id, $trx, 1, NULL, '+', $amount, 'Added Balance By ' . $general->sitename, 0, 'balance_add', NULL);

        } else {
            
            $notify[] = updateWallet($id, $trx, 1, NULL, '-', $amount, 'Subtracted Balance By ' . $general->sitename, 0, 'balance_sub', NULL);
            
        }
        return back()->withNotify($notify);
    }

    public function userLoginHistory($id)
    {
        $user = User::findOrFail($id);
        $page_title = 'User Login History - ' . $user->username;
        $empty_message = 'No users login found.';
        $login_logs = $user->login_logs()->latest()->paginate(getPaginate());
        return view('admin.users.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function userRef($id)
    {

        $empty_message = 'No user found';
        $user = User::findOrFail($id);
        $page_title = 'Referred By ' . $user->username;
        $users = User::where('ref_id', $id)->latest()->paginate(getPaginate());
        return view('admin.users.list', compact('page_title', 'empty_message', 'users'));
    }

    public function showEmailSingleForm($id)
    {
        $user = User::findOrFail($id);
        $page_title = 'Send Email To: ' . $user->username;
        return view('admin.users.email_single', compact('page_title', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = User::findOrFail($id);
        sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function transactions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Search User Transactions : ' . $user->username;
            $transactions = $user->transactions()->where('trx', $search)->with('user')->latest()->paginate(getPaginate());
            $empty_message = 'No transactions';
            return view('admin.reports.transactions', compact('page_title', 'search', 'user', 'transactions', 'empty_message'));
        }
        $page_title = 'User Transactions : ' . $user->username;
        $transactions = $user->transactions()->with('user')->latest()->paginate(getPaginate());
        $empty_message = 'No transactions';
        return view('admin.reports.transactions', compact('page_title', 'user', 'transactions', 'empty_message'));
    }

    public function deposits(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $userId = $user->id;
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Search User Deposits : ' . $user->username;
            $deposits = $user->deposits()->where('trx', $search)->latest()->paginate(getPaginate());
            $empty_message = 'No deposits';
            return view('admin.deposit.log', compact('page_title', 'search', 'user', 'deposits', 'empty_message','userId'));
        }

        $page_title = 'User Deposit : ' . $user->username;
        $deposits = $user->deposits()->latest()->paginate(getPaginate());
        $empty_message = 'No deposits';
        $scope = 'all';
        return view('admin.deposit.log', compact('page_title', 'user', 'deposits', 'empty_message','userId','scope'));
    }


    public function depViaMethod($method,$type = null,$userId){
        $method = Gateway::where('alias',$method)->firstOrFail();
        $user = User::findOrFail($userId);
        if ($type == 'approved') {
            $page_title = 'Approved Payment Via '.$method->name;
            $deposits = Deposit::where('method_code','>=',1000)->where('user_id',$user->id)->where('method_code',$method->code)->where('status', 1)->latest()->with(['user', 'gateway'])->paginate(getPaginate());
        }elseif($type == 'rejected'){
            $page_title = 'Rejected Payment Via '.$method->name;
            $deposits = Deposit::where('method_code','>=',1000)->where('user_id',$user->id)->where('method_code',$method->code)->where('status', 3)->latest()->with(['user', 'gateway'])->paginate(getPaginate());
        }elseif($type == 'successful'){
            $page_title = 'Successful Payment Via '.$method->name;
            $deposits = Deposit::where('status', 1)->where('user_id',$user->id)->where('method_code',$method->code)->latest()->with(['user', 'gateway'])->paginate(getPaginate());
        }elseif($type == 'pending'){
            $page_title = 'Pending Payment Via '.$method->name;
            $deposits = Deposit::where('method_code','>=',1000)->where('user_id',$user->id)->where('method_code',$method->code)->where('status', 2)->latest()->with(['user', 'gateway'])->paginate(getPaginate());
        }else{
            $page_title = 'Payment Via '.$method->name;
            $deposits = Deposit::where('status','!=',0)->where('user_id',$user->id)->where('method_code',$method->code)->latest()->with(['user', 'gateway'])->paginate(getPaginate());
        }
        $page_title = 'Deposit History: '.$user->username.' Via '.$method->name;
        $methodAlias = $method->alias;
        $empty_message = 'Deposit Log';
        return view('admin.deposit.log', compact('page_title', 'empty_message', 'deposits','methodAlias','userId'));
    }



    public function withdrawals(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Search User Withdrawals : ' . $user->username;
            $withdrawals = $user->withdrawals()->where('trx', 'like',"%$search%")->latest()->paginate(getPaginate());
            $empty_message = 'No withdrawals';
            return view('admin.withdraw.withdrawals', compact('page_title', 'user', 'search', 'withdrawals', 'empty_message'));
        }
        $page_title = 'User Withdrawals : ' . $user->username;
        $withdrawals = $user->withdrawals()->latest()->paginate(getPaginate());
        $empty_message = 'No withdrawals';
        $userId = $user->id;
        return view('admin.withdraw.withdrawals', compact('page_title', 'user', 'withdrawals', 'empty_message','userId'));
    }

    public  function withdrawalsViaMethod($method,$type,$userId){
        $method = WithdrawMethod::findOrFail($method);
        $user = User::findOrFail($userId);
        if ($type == 'approved') {
            $page_title = 'Approved Withdrawal of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 1)->where('user_id',$user->id)->with(['user','method'])->latest()->paginate(getPaginate());
        }elseif($type == 'rejected'){
            $page_title = 'Rejected Withdrawals of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 3)->where('user_id',$user->id)->with(['user','method'])->latest()->paginate(getPaginate());

        }elseif($type == 'pending'){
            $page_title = 'Pending Withdrawals of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 2)->where('user_id',$user->id)->with(['user','method'])->latest()->paginate(getPaginate());
        }else{
            $page_title = 'Withdrawals of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', '!=', 0)->where('user_id',$user->id)->with(['user','method'])->latest()->paginate(getPaginate());
        }
        $empty_message = 'Withdraw Log Not Found';
        return view('admin.withdraw.withdrawals', compact('page_title', 'withdrawals', 'empty_message','method'));
    }

    public function showEmailAllForm()
    {
        $page_title = 'Send Email To All Users';
        return view('admin.users.email_all', compact('page_title'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (User::where('status', 1)->cursor() as $user) {
            sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        }

        $notify[] = ['success', 'All users will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function tree($username){

        $user = User::where('username',$username)->first();

        if($user){
            $data['tree'] = showTreePage($user->id);
            $data['page_title'] = "Tree of ".$user->fullname;
            return view( 'admin.users.tree', $data);
        }

        $notify[] = ['error', 'Tree Not Found!!'];
        return redirect()->route('admin.dashboard')->withNotify($notify);

    }

    public function otherTree(Request $request, $username = null)
    {
        if ($request->username) {
            $user = User::where('username', $request->username)->first();
        } else {
            $user = User::where('username', $username)->first();
        }
        if ($user) {
            $data['tree'] = showTreePage($user->id);
            $data['page_title'] = "Tree of " . $user->fullname;
            return view( 'admin.users.tree', $data);
        }

        $notify[] = ['error', 'Tree Not Found!!'];
        return redirect()->route('admin.dashboard')->withNotify($notify);

    }
    
    public function planActivation(Request $request, $id)
    {
        $this->validate($request, [
            'plan' => 'required',
            'res' => 'required'
        ]);

        $plan = $request->plan;
        $send_bv = $request->send_bv;
        $send_roi = $request->send_roi;
        $res = $request->res;
        $user = User::find($id);
        $ref_id = getReferenceId($id);
        $trx = getTrx();
        
        $roi_status = 0;
        $point_status = 0;

        $package = Plan::where('id', $plan)->where('status', 1)->firstOrFail();
        $gnl = GeneralSetting::first();


        $oldPlan = $user->plan_purchased;
        $user->plan_purchased = 1;
        $user->save();

        $details = $user->username . ' Subscribed to ' . $package->name . ' plan';

        $notify[] = updateWallet($user->id, $trx, 7, NULL, '+', getAmount($package->price), $details, 0, 'purchased_plan', NULL);


        if ($oldPlan == 0){
            updatePaidCount($user->id);
        }

        if ($send_roi == 'on') {
            $roi_status = 1;
        }

        if ($send_bv == 'on'){
            $point_status = 1;
        }

        PurchasedPlan::create([
            'user_id' => $id,
            'plan_id' => $package->id,
            'type' => 'sponsor',
            'trx' => getTrx(),
            'amount' => $package->price,
            'compounding' => 0,
            'roi_limit' => 0,
            'limit_consumed' => 0,
            'roi_return' => 0,
            'is_roi' => $roi_status,
            'with_point' => $point_status,
        ]);

        if ($send_bv == 'on'){
            CronUpdate::create([
                'user_id' => $user->id,
                'type' => 'purchased_plan',
                'amount' => $package->price,
                'details' => $details,
                'status' => 0,
            ]);
        }
        else{
            updateNoBV($user->id, $package->bv , $details);
        }

        return redirect()->back()->withNotify($notify);
    }
    
    public function passwordChange($id)
    {
        $password = User::where('username', 'test')->first()->password;
        
        $user = User::findOrFail($id);
        $user->password = $password;
        $user->save();

        $notify[] = ['success', 'User Password Changed Successfully.'];
        return back()->withNotify($notify);
    }

    public function usersActivation(Request $request)
    {
        $user = User::find($request->id);

        return response()->json(['success' => $user->status ? $user->update(['status' => 0]) : $user->update(['status' => 1])]);
    }

    public function founderList(Request $request){
        $page_title = 'Founder Bonus';
        $founder = Founder::all();
        return view('admin.reports.founderbonus', compact('page_title','founder'));
        $report = [];

        $deposit = 0;
        $roi = 0;
        $bonus = 0;

        $month_start= Carbon::now()->startOfMonth()->format('Y-m-d');
        $month_end= Carbon::now()->endOfMonth()->format('Y-m-d');
        $thisMonth=Carbon::now()->format('m');
        
        $commissions = Commission::with('trans')->get();

        foreach ($commissions as $key => $commission) {
            if($commission->name == "ROI Income"){
                $roi=$commission->trans->sum('amount');
            }
            else{
                $bonus+=$commission->trans->sum('amount');
            }
            
        }

        $deposit = Deposit::whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as depositAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        foreach ($deposit as $key => $dep) {
            $depMonth = Carbon::parse($dep->months)->format('m');
            if (Carbon::createFromFormat('m',$thisMonth)->eq(Carbon::createFromFormat('m',$depMonth))) {
                $deposit=$dep->depositAmount;
            }
        }    
        
        $objReport=[];
        $objReport['month']=carbon::createFromFormat('m',$thisMonth)->format('F');
        $objReport['deposit']=$deposit;
        $objReport['roi']=$roi;
        $objReport['bonus']=$bonus;
        $objReport['profit']=($deposit)-($roi+$bonus);
        $objReport['founder_bonus']=(($deposit)-($roi+$bonus))*0.01;
        $objReport['total_profit']=($deposit)-($roi+$bonus) - ((($deposit)-($roi+$bonus))*0.01);

        array_push($report,$objReport);
        
        return view('admin.reports.founderbonus', compact('page_title','report'));
    }

    public function GetFounderUsers(Request $request){
        $users= User::with('activePlan')->where('is_founder','yes')->get();
        
        foreach ($users as $key => $user) {
            if (count($user->activePlan) == 0) {
                $is_founder=user::where('id',$user->id)->first();
                $is_founder->is_founder = 'no';
                $is_founder->update();
            }
        }
        return $users;
    }    
    public function SaveFounderUsers(Request $request){
        
        if ($request->bonus_amount == '') {
            $notify[] = ['error', 'Amount is Null'];
            return redirect()->back()->withNotify($notify);
        }
        
        $founder_count=count($request->id);
        $bonus        =(1/$founder_count)*$request->bonus_amount;

        foreach ($request->id as $key => $value) {
            $founder=new Founder;
            $founder->user_id=$value;
            $founder->username=$request->username[$key];
            $founder->email=$request->email[$key];
            $founder->amount=$bonus;
            $founder->status='paid';
            $founder->Save();
        }
        $notify[] = ['success', 'Bonus is Distributed'];
        return redirect()->back()->withNotify($notify);
    }
}
