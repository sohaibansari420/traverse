<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PurchasedPlan;
use App\Models\BvLog;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Models\Wallet;
use App\Models\Epin;
use App\Models\GeneralSetting;
use App\Models\UserFamily;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportsData($id, Request $request)
    {                
        if($id == "userWallets"){
            $columns = array( 
                0 =>'id', 
                1 =>'created_at', 
                2 =>'username',
                3 =>'wallet',
                4 =>'balance',
                5 =>'status',
            );
            if($request->type == "all"){
                if($request->startDate){
                    $totalData = UserWallet::where('created_at','>',Carbon::parse($request->startDate))->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))->count();
                }else{
                    $totalData = UserWallet::latest()->count();
                }    
            }else{
                if($request->startDate){
                    $totalData = UserWallet::where('wallet_id', $request->type )->where('created_at','>',Carbon::parse($request->startDate))->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))->count();
                }else{
                    $totalData = UserWallet::where('wallet_id', $request->type )->count();
                } 
            }
            
        }elseif($id == "purchasedPlans"){
            $columns = array( 
                0 =>'id', 
                1 =>'created_at',
                2 =>'user_id',
                3 =>'plan_id',
                4 =>'type',
                5 =>'trx',
                6 =>'amount',
                7 =>'compounding',
                8 =>'roi_limit',
                9 =>'limit_consumed',
                10 =>'roi_return',
                11 =>'is_roi',
                12 =>'with_point',
                13 =>'auto_renew',
                14 =>'auto_compounding',
                15 =>'is_expired',
            );
            if($request->type == "all"){
                if($request->startDate){
                    $totalData = PurchasedPlan::where('created_at','>',Carbon::parse($request->startDate))->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))->count();
                }else{
                    $totalData = PurchasedPlan::latest()->count();
                }    
            }else{
                if($request->startDate){
                    $totalData = PurchasedPlan::where('type', $request->type)->where('created_at','>',Carbon::parse($request->startDate))->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))->count();
                }else{
                    $totalData = PurchasedPlan::where('type', $request->type)->count();
                } 
            }
        }
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        { 
            if($id == "userWallets"){
                if($request->type == "all"){
                    if($request->startDate){
                        $records = UserWallet::offset($start)
                                ->where('created_at','>',Carbon::parse($request->startDate))
                                ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                    }else{
                        $records = UserWallet::offset($start)
                             ->limit($limit)
                             ->orderBy($order,$dir)
                             ->get();
                    }
                }else{
                    if($request->startDate){
                        $records = UserWallet::offset($start)
                                ->where('wallet_id', $request->type )
                                ->where('created_at','>',Carbon::parse($request->startDate))
                                ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                    }else{
                        $records = UserWallet::offset($start)
                             ->where('wallet_id', $request->type )
                             ->limit($limit)
                             ->orderBy($order,$dir)
                             ->get();
                    }
                }
            }elseif($id == "purchasedPlans"){
                if($request->type == "all"){
                    if($request->startDate){
                        $records = PurchasedPlan::offset($start)
                            ->where('created_at','>',Carbon::parse($request->startDate))
                            ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
                    }else{
                        $records = PurchasedPlan::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
                    }    
                }else{
                    if($request->startDate){
                        $records = PurchasedPlan::offset($start)
                            ->where('created_at','>',Carbon::parse($request->startDate))
                            ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                            ->where('type', $request->type)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
                    }else{
                        $records = PurchasedPlan::offset($start)
                            ->where('type', $request->type)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
                    }  
                }
            }    
        }
        else {
            $search = $request->input('search.value'); 

            if($id == "userWallets"){
                if($request->type == "all"){
                    $user = User::where('username', $search)->first();
                    if($user){
                        if($request->startDate){
                            $records =  UserWallet::where('user_id',$user->id)
                                ->where('created_at','>',Carbon::parse($request->startDate))
                                ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                        
                            $totalFiltered = UserWallet::where('user_id',$user->id)
                                    ->where('created_at','>',Carbon::parse($request->startDate))
                                    ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                    ->count();
                        }else{
                            $records =  UserWallet::where('id','LIKE',"{$search}%")
                                ->orWhere('user_id',$user->id)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                        
                            $totalFiltered = UserWallet::where('id','LIKE',"{$search}%")
                                    ->orWhere('user_id',$user->id)
                                    ->count();
                        }
                    }
                }else{
                    $user = User::where('username', $search)->first();
                    if($user){
                        if($request->startDate){
                            $records =  UserWallet::where('user_id',$user->id)
                                ->where('wallet_id', $request->type )
                                ->where('created_at','>',Carbon::parse($request->startDate))
                                ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                        
                            $totalFiltered = UserWallet::where('user_id',$user->id)
                                    ->where('wallet_id', $request->type )
                                    ->where('created_at','>',Carbon::parse($request->startDate))
                                    ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                    ->count();
                        }else{
                            $records =  UserWallet::where('user_id',$user->id)
                                ->where('wallet_id', $request->type )
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
                        
                            $totalFiltered = UserWallet::where('user_id',$user->id)
                                    ->where('wallet_id', $request->type )
                                    ->count();
                        }
                    }
                }
            }elseif($id == "purchasedPlans"){
                if($request->type == "all"){
                    if($request->startDate){
                        $user = User::where('username', $search)->first();
                        if($user){
                            $records =  PurchasedPlan::where('user_id',$user->id)
                                    ->where('created_at','>',Carbon::parse($request->startDate))
                                    ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                            
                            $totalFiltered = PurchasedPlan::where('user_id',$user->id)
                                    ->where('created_at','>',Carbon::parse($request->startDate))
                                    ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                    ->count();
                        }
                    }else{
                        $user = User::where('username', $search)->first();
                        if($user){
                            $records =  PurchasedPlan::where('id','LIKE',"{$search}%")
                                    ->orWhere('user_id',$user->id)
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                            
                            $totalFiltered = PurchasedPlan::where('id','LIKE',"{$search}%")
                                    ->orWhere('user_id',$user->id)
                                    ->count();
                        }
                    }    
                }else{
                    if($request->startDate){
                        $user = User::where('username', $search)->first();
                        if($user){
                            $records =  PurchasedPlan::where('user_id',$user->id)
                                    ->where('type', $request->type)
                                    ->where('created_at','>',Carbon::parse($request->startDate))
                                    ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                            
                            $totalFiltered = PurchasedPlan::where('user_id',$user->id)
                                    ->where('type', $request->type)
                                    ->where('created_at','>',Carbon::parse($request->startDate))
                                    ->where('created_at','<=',Carbon::parse($request->endDate)->addDays(1))
                                    ->count();
                        }
                    }else{
                        $user = User::where('username', $search)->first();
                        if($user){
                            $records =  PurchasedPlan::where('user_id',$user->id)
                                    ->where('type', $request->type)
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
                            
                            $totalFiltered = PurchasedPlan::where('user_id',$user->id)
                                    ->where('type', $request->type)
                                    ->count();
                        }
                    }   
                }
            }
        }

        $data = array();
        if(!empty($records))
        {
            foreach ($records as $record)
            {
                $user_link =  route('admin.users.detail',$record->user_id);

                if($id == "userWallets"){
                    $nestedData['id'] = $record->id;
                    $nestedData['created_at'] = showDateTime($record->created_at);
                    $nestedData['username'] = "<a href='{$user_link}'>{$record->user->username}</a>";
                    $nestedData['wallet'] = $record->wallet->name;
                    $nestedData['balance'] = $record->balance;
                    if($record->status){
                        $nestedData['status'] = "<span class='text--small badge font-weight-normal badge--success'>Active</span>";
                    }
                    else{
                        $nestedData['status'] = "<span class='text--small badge font-weight-normal badge--danger'>Inactive</span>";
                    }
                    $nestedData['action'] = "<button type='button' class='icon-btn edit' data-toggle='tooltip' data-id='{$record->id}' data-status='{$record->status}' data-original-title='Edit'><i class='la la-pencil'></i></button>";
                }elseif($id == "purchasedPlans"){
                    $nestedData['id'] = $record->id;
                    $nestedData['created_at'] = showDateTime($record->created_at);
                    $nestedData['user_id'] = "<a href='{$user_link}'>{$record->user->username}</a>";
                    $nestedData['plan_id'] = $record->plan->name;
                    $nestedData['type'] = $record->type;
                    $nestedData['trx'] = $record->trx;
                    $nestedData['amount'] = getAmount($record->amount);
                    $nestedData['compounding'] = getAmount($record->compounding);
                    $nestedData['roi_limit'] = $record->roi_limit;
                    $nestedData['limit_consumed'] = $record->limit_consumed;
                    $nestedData['roi_return'] = $record->roi_return;
                    if($record->is_roi){
                        $nestedData['is_roi'] = "<span class='text--small badge font-weight-normal badge--success'>Active</span>";
                    }
                    else{
                        $nestedData['is_roi'] = "<span class='text--small badge font-weight-normal badge--danger'>Inactive</span>";
                    }
                    if($record->with_point){
                        $nestedData['with_point'] = "<span class='text--small badge font-weight-normal badge--success'>With Point</span>";
                    }
                    else{
                        $nestedData['with_point'] = "<span class='text--small badge font-weight-normal badge--danger'>Without Point</span>";
                    }
                    if($record->auto_renew){
                        $nestedData['auto_renew'] = "<span class='text--small badge font-weight-normal badge--success'>Active</span>";
                    }
                    else{
                        $nestedData['auto_renew'] = "<span class='text--small badge font-weight-normal badge--danger'>Inactive</span>";
                    }
                    if($record->auto_compounding){
                        $nestedData['auto_compounding'] = "<span class='text--small badge font-weight-normal badge--success'>Active</span>";
                    }
                    else{
                        $nestedData['auto_compounding'] = "<span class='text--small badge font-weight-normal badge--danger'>Inactive</span>";
                    }
                    if($record->is_expired){
                        $nestedData['is_expired'] = "<span class='text--small badge font-weight-normal badge--danger'>Expired</span>";
                    }
                    else{
                        $nestedData['is_expired'] = "<span class='text--small badge font-weight-normal badge--success'>Active</span>";
                    }
                    $nestedData['action'] = "<button type='button' class='icon-btn edit' data-toggle='tooltip' data-id='{$record->id}' data-is_roi='{$record->is_roi}' data-is_expired='{$record->is_expired}' data-original-title='Edit'><i class='la la-pencil'></i></button>";
                }
                
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

    public function userWallets($type, Request $request)
    {
        if($request->userID){
            
        }
        elseif($request->date){
            $search = $request->date;
            if (!$search) {
                return back();
            }
            $date = explode('-',$search);

            if(!(@strtotime($date[0]) && @strtotime($date[1]))){
                $notify[]=['error','Please provide valid date'];
                return back()->withNotify($notify);
            }

            $start  = @$date[0];
            $end    = @$date[1];

            if($type == "all"){
                $title_name = $type;
            }
            else{
                $title_name = Wallet::where('id', $type)->first()->name;
            }

            $page_title = $start . ' - ' . $end . ' - ' . $title_name  . ' - User Wallets';
            $data_type = "userWallets";
            return view('admin.reports.userWallets', compact('page_title', 'data_type', 'start', 'end', 'type'));
        }
        else{
            if($type == "all"){
                $title_name = $type;
            }
            else{
                $title_name = Wallet::where('id', $type)->first()->name;
            }
            
            $page_title = $title_name . ' - User Wallets';
            $data_type = "userWallets";
            return view('admin.reports.userWallets', compact('page_title', 'data_type', 'type'));
        }
    }

    public function userWalletUpdate(Request $request)
    {
        $user_wallet                = UserWallet::find($request->id);
        $user_wallet->status        = $request->status?1:0;
        $user_wallet->save();

        $notify[] = ['success', 'User Wallet Updated Successfully.'];
        return back()->withNotify($notify);
    }

    public function planPurchased($type, Request $request)
    {
        if($request->userID){
            $user = User::find($request->userID);
            $page_title = $user->username . ' - Purchased Plans';
            $empty_message = 'No Plan found';
            $plans = PurchasedPlan::where('user_id', $user->id)->paginate(getPaginate());;
            return view('admin.plan.purchased', compact('page_title', 'plans', 'empty_message'));
        }
        elseif($request->date){
            $search = $request->date;
            if (!$search) {
                return back();
            }
            $date = explode('-',$search);

            if(!(@strtotime($date[0]) && @strtotime($date[1]))){
                $notify[]=['error','Please provide valid date'];
                return back()->withNotify($notify);
            }

            $start  = @$date[0];
            $end    = @$date[1];

            $page_title = $start . ' - ' . $end . ' - ' . $type . ' - Purchased Plans';
            $data_type = "purchasedPlans";
            return view('admin.reports.purchasedPlans', compact('page_title', 'data_type', 'start', 'end', 'type'));
        }
        else{
            
            $page_title = $type. ' - Purchased Plans';
            $data_type = "purchasedPlans";
            return view('admin.reports.purchasedPlans', compact('page_title', 'data_type', 'type'));
        }
    }

    public function planPurchasedUpdate(Request $request)
    {

        $plan                       = PurchasedPlan::find($request->id);
        $plan->is_roi               = $request->is_roi?1:0;
        $plan->is_expired           = $request->is_expired?0:1;
        $plan->save();

        $notify[] = ['success', 'Purchased Plan Updated Successfully.'];
        return back()->withNotify($notify);
    }
    
    public function planPurchasedDelete(Request $request)
    {
        $id = $request->id;
        $plan = PurchasedPlan::find($id);
        $user_id = $plan->user_id;
        $user = User::find($user_id);
        if ($plan) {
            $plan->delete();
            $transactions = Transaction::where('plan_trx', $plan->trx)->count();
            if($transactions > 0){
                $transactions = Transaction::where('plan_trx', $plan->trx)->delete();
            }
            if(checkUserPlanCount($user_id) > 0){
                $plan_id = getUserHigherPlan($user_id)->plan_id;
                $user_family = UserFamily::where('mem_id', $user_id)->get();
                foreach($user_family as $data){
                    $data->plan_id = $plan_id;
                    $data->update();
                }
                $user->plan_purchased = 1;
                $user->update();
            }
            else{
                $user_family = UserFamily::where('mem_id', $user_id)->get();
                foreach($user_family as $data){
                    $data->plan_id = 0;
                    $data->update();
                }
                $user->plan_purchased = 0;
                $user->update();
            }
            $notify[] = ['success', 'Plan has been deleted.'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', 'Plan delete error.'];
        return back()->withNotify($notify);
    }
    
    public function bvLog(Request $request)
    {

        if ($request->type) {
            if ($request->type == 'leftBV') {
                $data['page_title'] = "Left BV";
                $data['logs'] = BvLog::where('position', 1)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
            } elseif ($request->type == 'rightBV') {
                $data['page_title'] = "Right BV";
                $data['logs'] = BvLog::where('position', 2)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
            }elseif ($request->type == 'cutBV') {
                $data['page_title'] = "Cut BV";
                $data['logs'] = BvLog::where('trx_type', '-')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
            } else {
                $data['page_title'] = "All Paid BV";
                $data['logs'] = BvLog::where('trx_type', '+')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
            }
        }else{
            $data['page_title'] = "BV LOG";
            $data['logs'] = BvLog::orderBy('id', 'desc')->paginate(config('constants.table.default'));
        }

        $data['empty_message'] = 'No data found';
        return view('admin.reports.bvLog', $data);
    }

    public function singleBvLog(Request $request, $id)
    {

        if($request->search != ''){
            $user = User::where('username',$request->search)->first();
            if($user == NULL){
                $notify[] = ['error', 'Invalid Username'];
                return  redirect()->back()->withNotify($notify);
            }
        }
        else{
            $user = User::findOrFail($id);
        }
        if ($request->type) {
            if ($request->type == 'leftBV') {
                $data['page_title'] = $user->username . " - Left BV";
                $data['logs'] = BvLog::where('user_id', $user->id)->where('position', 1)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
                $data['total'] = BvLog::where('user_id', $user->id)->where('position', 1)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->sum('amount');
            } elseif ($request->type == 'rightBV') {
                $data['page_title'] = $user->username . " - Right BV";
                $data['logs'] = BvLog::where('user_id', $user->id)->where('position', 2)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
                $data['total'] = BvLog::where('user_id', $user->id)->where('position', 2)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->sum('amount');
            } elseif ($request->type == 'cutBV') {
                $data['page_title'] = $user->username . " - All Cut BV";
                $data['logs'] = BvLog::where('user_id', $user->id)->where('trx_type', '-')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
                $data['total'] = BvLog::where('user_id', $user->id)->where('trx_type', '-')->orderBy('id', 'desc')->with('user')->sum('amount');
            } else {
                $data['page_title'] = $user->username . " - All Paid BV";
                $data['logs'] = BvLog::where('user_id', $user->id)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
                $data['total'] = BvLog::where('user_id', $user->id)->where('trx_type', '+')->orderBy('id', 'desc')->with('user')->sum('amount');
            }
        }else{
            $data['page_title'] = $user->username . " - All  BV";
            $data['logs'] = BvLog::where('user_id', $user->id)->orderBy('id', 'desc')->with('user')->paginate(config('constants.table.default'));
            $data['total'] = BvLog::where('user_id', $user->id)->orderBy('id', 'desc')->with('user')->sum('amount');

        }

        $data['empty_message'] = 'No data found';
        return view('admin.reports.bvLog', $data);
    }

    public function invest(Request $request)
    {
        if ($request->user)
        {
            $user = User::findOrFail($request->user);
            $page_title = $user->username . ' - Invest Logs';
            $transactions = Transaction::where('user_id', $user->id)->where('remark', 'purchased_plan')->with('user')->latest()->paginate(getPaginate());
        }else {
            $page_title = 'Invest Logs';
            $transactions = Transaction::where('remark', 'purchased_plan')->with('user')->latest()->paginate(getPaginate());
        }

        $empty_message = 'No transactions.';
        $type = 'purchased_plan';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }
    public function epin(Request $request)
    {
        if ($request->user)
        {
            $user = User::findOrFail($request->user);
            $page_title = $user->username . ' - Epin Logs';
            $transactions = Epin::where('created_by', $user->id)->latest()->paginate(getPaginate());
        }else {
            $page_title = 'Epin Logs';
            $transactions = Epin::latest()->paginate(getPaginate());
        }

        $empty_message = 'No transactions.';
        $type = 'purchased_plan';
        return view('admin.reports.epin', compact('page_title', 'transactions', 'empty_message', 'type'));
    }
    public function epinSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $type = $request->type;
        $page_title = 'Epin Search - ' . $search;
        $empty_message = 'No transactions.';

        $transactions = Epin::with('createdBy')->whereHas('createdBy', function ($user) use ($search) {
            $user->where('username',$search);
        })->orderBy('id','desc')->paginate(getPaginate());
       
        return view('admin.reports.epin', compact('page_title', 'transactions', 'empty_message', 'type'));
    }
    public function transaction()
    {
        $page_title = 'Transaction Logs';
        $transactions = Transaction::with('user')->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No transactions.';
        $type = 'all';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function transactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $type = $request->type;
        $page_title = 'Transactions Search - ' . $search;
        $empty_message = 'No transactions.';

        if($type == 'all'){
            $transactions = Transaction::with('user')->whereHas('user', function ($user) use ($search) {
                $user->where('username', 'like',"%$search%");
            })->orWhere('trx', $search)->orderBy('id','desc')->paginate(getPaginate());
        }
        else{
            $transactions = Transaction::with('user')->where('remark', $type)->whereHas('user', function ($user) use ($search) {
                $user->where('username', 'like',"%$search%");
            })->orWhere('trx', $search)->orderBy('id','desc')->paginate(getPaginate());
        }

        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function transactionDateSearch(Request $request){
        $search = $request->date;
        $type = $request->type;
        if (!$search) {
            return back();
        }
        $date = explode('-',$search);


        if(!(@strtotime($date[0]) && @strtotime($date[1]))){
            $notify[]=['error','Please provide valid date'];
            return back()->withNotify($notify);
        }

        $start  = @$date[0];
        $end    = @$date[1];


        if($type == 'all'){
            if ($start) {
                $transactions = Transaction::where('created_at','>',Carbon::parse($start)->subDays(1))->where('created_at','<=',Carbon::parse($start)->addDays(1));
            }
            if($end){
                $transactions = Transaction::where('created_at','>',Carbon::parse($start)->subDays(1))->where('created_at','<',Carbon::parse($end)->addDays(1));
            }
        }
        else{
            if ($start) {
                $transactions = Transaction::where('remark', $type)->where('created_at','>',Carbon::parse($start)->subDays(1))->where('created_at','<=',Carbon::parse($start)->addDays(1));
            }
            if($end){
                $transactions = Transaction::where('remark', $type)->where('created_at','>',Carbon::parse($start)->subDays(1))->where('created_at','<',Carbon::parse($end)->addDays(1));
            }
        }
        
        
        
        $transactions = $transactions->with(['user'])->latest()->paginate(getPaginate());
        $page_title = 'Logs';
        $empty_message = 'Not Found';
        $dateSearch = $search;
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message','dateSearch', 'type'));
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $page_title = 'User Login History Search - ' . $search;
            $empty_message = 'No search result found.';
            $login_logs = UserLogin::whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id','desc')->paginate(getPaginate());
            return view('admin.reports.logins', compact('page_title', 'empty_message', 'search', 'login_logs'));
        }
        $page_title = 'User Login History';
        $empty_message = 'No users login found.';
        $login_logs = UserLogin::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.reports.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_ip',$ip)->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No users login found.';
        return view('admin.reports.logins', compact('page_title', 'empty_message', 'login_logs'));

    }

    public function wallet(Request $request)
    {
        $id=$request->walletID;
        if ($id)
        {
            if($request->userID){
                $wallet = Wallet::where('id', $id)->first();
                $user = User::findOrFail($request->userID);
                $page_title = $user->username . ' - ' . $wallet->name . ' Logs';
                $transactions = Transaction::where('user_id', $user->id)->where('wallet_id', $wallet->id)->with('user')->latest()->paginate(getPaginate());
            }
            else{
                $wallet = Wallet::where('id', $id)->first();
                $page_title = $wallet->name . ' Logs';
                $transactions = Transaction::where('wallet_id', $wallet->id)->with('user')->latest()->paginate(getPaginate()); 
            }
        }else {
            if($request->userID){
                $user = User::findOrFail($request->userID);
                $page_title = $user->username . ' - Wallet Logs';
                $transactions = Transaction::where('user_id', $user->id)->where('wallet_id', '!=', NULL)->with('user')->latest()->paginate(getPaginate());    
            }
            else{
                $page_title = 'Wallet Logs';
                $transactions = Transaction::where('wallet_id', '!=', NULL)->with('user')->latest()->paginate(getPaginate());   
            }
        }

        $empty_message = 'No transactions.';
        $type = 'wallet';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function commission(Request $request)
    {
        $id = $request->commissionID;
        if ($id)
        {
            if($request->userID){
                $commission = Commission::where('id', $id)->first();
                $user = User::findOrFail($request->userID);
                $page_title = $user->username . ' - ' . $commission->name . ' Logs';
                $transactions = Transaction::where('user_id', $user->id)->where('commission_id', $commission->id)->with('user')->latest()->paginate(getPaginate());
            }
            else{
                $commission = Commission::where('id', $id)->first();
                $page_title = $commission->name . ' Logs';
                $transactions = Transaction::where('commission_id', $commission->id)->with('user')->latest()->paginate(getPaginate()); 
            }
        }else {
            if($request->userID){
                $user = User::findOrFail($request->userID);
                $page_title = $user->username . ' - Commission Logs';
                $transactions = Transaction::where('user_id', $user->id)->where('commission_id', '!=', NULL)->with('user')->latest()->paginate(getPaginate());    
            }
            else{
                $page_title = 'Commission Logs';
                $transactions = Transaction::where('commission_id', '!=', NULL)->with('user')->latest()->paginate(getPaginate());   
            }
        }

        $empty_message = 'No transactions.';
        $type = 'commission';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function refCom(Request $request)
    {
        if ($request->userID)
        {
            $user = User::findOrFail($request->userID);
            $page_title = $user->username . ' - Referral Commission Logs';
            $transactions = Transaction::where('user_id', $user->id)->where('remark', 'sponsor_bonus')->with('user')->latest()->paginate(getPaginate());
        }else {
            $page_title = 'Referral Commission Logs';
            $transactions = Transaction::where('remark', 'sponsor_bonus')->with('user')->latest()->paginate(getPaginate());
        }

        $empty_message = 'No transactions.';
        $type = 'sponsor_bonus';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function vipCom(Request $request)
    {
        if ($request->userID)
        {
            $user = User::findOrFail($request->userID);
            $page_title = $user->username . ' - VIP Commission Logs';
            $transactions = Transaction::where('user_id', $user->id)->where('remark', 'vip_commission')->with('user')->latest()->paginate(getPaginate());
        }else {
            $page_title = 'VIP Commission Logs';
            $transactions = Transaction::where('remark', 'vip_commission')->with('user')->latest()->paginate(getPaginate());
        }

        $empty_message = 'No transactions.';
        $type = 'vip_commission';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function roiIncome(Request $request)
    {
        if ($request->userID)
        {
            $user = User::findOrFail($request->userID);
            $page_title = $user->username . ' - Daily Income Logs';
            $transactions = Transaction::where('user_id', $user->id)->where('remark', 'roi_income')->with('user')->latest()->paginate(getPaginate());
        }else {
            $page_title = 'Daily Income Logs';
            $transactions = Transaction::where('remark', 'roi_income')->with('user')->latest()->paginate(getPaginate());
        }

        $empty_message = 'No transactions.';
        $type = 'roi_income';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function binary(Request $request)
    {
        if ($request->userID)
        {
            $user = User::findOrFail($request->userID);
            $page_title = $user->username . ' - Binary Commission Logs';
            $transactions = Transaction::where('user_id', $user->id)->where('remark', 'binary_commission')->with('user')->latest()->paginate(getPaginate());
        }else {
            $page_title = 'Binary Commission Logs';
            $transactions = Transaction::where('remark', 'binary_commission')->with('user')->latest()->paginate(getPaginate());
        }

        $empty_message = 'No transactions.';
        $type = 'binary_commission';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message', 'type'));
    }

    public function deleteUnilevelBonus($id)
    {
        if (isset($id)) {
            $trnas = Transaction::find($id);

            if (isset($trnas)) {

                $user_id = $trnas->user_id;
                $wallet_id = $trnas->wallet_id;
                $amt = $trnas->amount;

                $user_wallet = UserWallet::where('user_id', $user_id)->where('wallet_id', $wallet_id)->first();

                if (isset($user_wallet)) {
                    $user_wallet->balance -= $amt;
                    $user_wallet->update();
                }

                $trnas->delete();
            }
        }
    }
    public function editComission($id){
        return $id;
    }
}
