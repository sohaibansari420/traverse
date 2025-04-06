<?php

namespace App\Http\Controllers;

use App\Models\BvLog;
use App\Models\CommissionDetail;
use App\Models\CronUpdate;
use App\Models\Epin;
use App\Models\GeneralSetting;
use App\Models\Media;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserFamily;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    function planIndex()
    {
        $data['page_title'] = "Packages";
        $data['plans'] = Plan::whereStatus(1)->orderBy('price')->distinct()->get();
        $data['myPlans'] = PurchasedPlan::where('user_id', Auth::id())->get();
        $data['myPlansAmounts'] = PurchasedPlan::where('user_id', Auth::id())->pluck('amount')->toArray();
        $data['planCount']=count($data['plans']);
        return view($this->activeTemplate . '.user.plan', $data);

    }

    public function planDetails(Request $request){
        $data['page_title'] = "Packages Details";
        $data['plans'] = Plan::whereStatus(1)->where('title',$request->title)->orderBy('price')->get();
        $data['myPlans'] = PurchasedPlan::where('user_id', Auth::id())->get();
        $data['myPlansAmounts'] = PurchasedPlan::where('user_id', Auth::id())->pluck('amount')->toArray();
        $data['planCount']=count($data['plans']);
        return view($this->activeTemplate . '.user.plan_details', $data);
    }

    function planStore(Request $request)
    {

        $this->validate($request, ['plan_id' => 'required|integer']);
        
        $package = Plan::where('id', $request->plan_id)->where('status', 1)->firstOrFail();
        $user = User::find(Auth::id());
        $ref_id = getReferenceId(Auth::id());
        $trx = getTrx();
        
        $type = 1;
        $auto_renew = 0;
       
        if($type == 1){
            $balance = UserWallet::where('user_id', $user->id)->where('wallet_id', 1)->first()->balance;
    
            if ($balance < $package->price) {
                $notify[] = ['error', 'Insufficient Balance'];
                return back()->withNotify($notify);
            }
            
            $details = $user->username . ' Subscribed to ' . $package->name . ' plan';
        
            $notify[] = updateWallet($user->id, $trx, 1, NULL, '-', getAmount($package->price), $details, 0, 'purchased_plan', NULL,'');
    
            $oldPlan = $user->plan_purchased;
            $user->plan_purchased = 1;
            $user->save();
    
            if ($oldPlan == 0){
                updatePaidCount($user->id);
            }
    
            PurchasedPlan::create([
                'user_id' => $user->id,
                'plan_id' => $package->id,
                'trx' => $trx,
                'amount' => $package->price,
                'compounding' => 0,
                'roi_limit' => 0,
                'limit_consumed' => 0,
                'roi_return' => 0,
                'auto_renew' => $auto_renew,
            ]);
    
            CronUpdate::create([
                'user_id' => $user->id,
                'type' => 'purchased_plan',
                'amount' => $package->price,
                'details' => $details,
                'status' => 0,
            ]);

            // if ($package->price >= 25000) {
            //     $user_founder=User::find($user->id);
            //     $user_founder->is_founder = 'yes';
            //     $user_founder->update();
            // }
    
            return redirect()->route('user.home')->withNotify($notify);
        }
        elseif($type == 2){
            $epin = $request->epin;
            
            $epins = Epin::where('epin', $epin)->where('status', 0)->count();
            
            if ($epins != 1) {
                $notify[] = ['error', 'Invalid E-Pin'];
                return back()->withNotify($notify);
            }

            $pin = Epin::where('epin', $epin)->where('status', 0)->firstOrFail();
     
            $pin->updated_by = $user->id;
            $pin->status = 2;
            $pin->save();
            
            $notify[] = ['success', 'Plan Subscribed With Epin Successfully, Will Be Activated After Admin Approval.'];
            
            /*$package = Plan::where('price', $pin->amount)->where('status', 1)->firstOrFail();
            
            $details = $user->username . ' Subscribed to ' . $package->name . ' plan with Epin: ' . $epin;
        
            $notify[] = updateTransaction($user->id, $trx, 1, NULL, '*', getAmount($package->price), $details, 0, 'purchased_plan', 'plan_purchased');

            $oldPlan = $user->plan_purchased;
            $user->plan_purchased = 1;
            $user->save();

            if ($oldPlan == 0){
                updatePaidCount($user->id);
            }

            PurchasedPlan::create([
                'user_id' => $user->id,
                'plan_id' => $package->id,
                'type' => 'epin',
                'trx' => $trx,
                'amount' => $package->price,
                'compounding' => 0,
                'roi_limit' => 0,
                'limit_consumed' => 0,
                'roi_return' => 0,
                'auto_renew' => $auto_renew,
            ]);

            CronUpdate::create([
                'user_id' => $user->id,
                'type' => 'purchased_plan',
                'amount' => $package->price,
                'details' => $details,
                'status' => 0,
            ]);*/

            
            return redirect()->route('user.home')->withNotify($notify);
        }
    }
    function planUpgrade(Request $request){
        $upgrade_id=0;
        foreach ($request->plans_select as $key => $value) {
            $val=explode("-",$value);
            if ($request->upgrade == $val[1]) {
                $upgrade_id=$val[0];    
            }
        }
        
        if ($upgrade_id == 0) {
            $notify[] = ['error', 'Select your new plan to proceed.'];
            return back()->withNotify($notify);
        }

        $plan_upgrade_id=$request->plan_upgrade_id;
        $plan_id=$request->upgrade;

        $this->validate($request, ['plan_upgrade_id' => 'required|integer']);
        
        $package = Plan::where('id', $upgrade_id)->where('status', 1)->firstOrFail();
        $user = User::find(Auth::id());
        $ref_id = getReferenceId(Auth::id());
        $trx = getTrx();
        
        $type = 'upgrade';
        $auto_renew = 0;
        
        if($type == 'upgrade'){
            $balance = UserWallet::where('user_id', $user->id)->where('wallet_id', 1)->first()->balance;
            $remainig_price=$package->price - $request->plan_amount;
            if ($balance < $remainig_price) {
                $notify[] = ['error', 'Insufficient Balance'];
                return back()->withNotify($notify);
            }
            
            $details = $user->username . ' Upgraded to ' . $package->name . ' plan';
            
            $notify[] = updateWallet($user->id, $trx, 1, NULL, '-', getAmount($remainig_price), $details, 0, 'upgrade_purchased_plan', NULL,'');
            
            $oldPlan = $user->plan_purchased;
            $user->plan_purchased = 1;
            $user->save();
    
            if ($oldPlan == 0){
                updatePaidCount($user->id);
            }
            $purchased_plans=PurchasedPlan::find($plan_id);
            $purchased_plans->amount = $package->price;
            $purchased_plans->plan_id = $upgrade_id;
            $purchased_plans->update();
    
            CronUpdate::create([
                'user_id' => $user->id,
                'type' => 'upgrade_plan',
                'amount' => $package->price,
                'details' => $details,
                'status' => 0,
            ]);
    
            return redirect()->route('user.home')->withNotify($notify);
        }
    }
    function planRenew(Request $request)
    {

        $this->validate($request, ['plan_id' => 'required|integer', 'wallet_id' => 'required']);
        
        $package = PurchasedPlan::where('id', $request->plan_id)->where('is_expired', 1)->firstOrFail();
        $user = User::find(Auth::id());
        $ref_id = getReferenceId(Auth::id());
        $trx = getTrx();
        $updated = $package->updated_at;
        $old_package = $package->trx;
       
        $balance = UserWallet::where('user_id', $user->id)->where('wallet_id', $request->wallet_id)->first()->balance;

        if ($balance < $package->plan->price) {
            $notify[] = ['error', 'Insufficient Balance'];
            return back()->withNotify($notify);
        }

        $details = $user->username . ' Renewal ' . $package->plan->name . ' plan';
    
        $notify[] = updateWallet($user->id, $trx, $request->wallet_id, NULL, '-', getAmount($package->plan->price), $details, 0, 'renew_plan', NULL,'');

        $user->plan_purchased = 1;
        $user->save();

        $package->limit_consumed = 0;
        $package->is_expired = 0;
        $package->trx = $trx;
        $package->save();

        $now_time = Carbon::now()->diffInHours($updated);

        if($now_time <= 24){
            $flused_income = Transaction::where(['wallet_id' => 6, 'user_id' => Auth::id(), 'plan_trx' => $old_package, 'trx_type' => '+'])
                    ->whereBetween('created_at', [new Carbon($updated), Carbon::now()])
                    ->get();

            foreach($flused_income as $data){
                $details = 'Return Flushed-Off '. getCommissionName($data->commission_id) . ' After Package Renewal';
                updateWallet($user->id, getTrx(), 6 , $data->commission_id, '-', getAmount($data->amount), $details, 0, str_replace(' ', '_', getCommissionName($data->commission_id)), $trx,''); 
                $commission = getCommission($data->commission_id);
                if($commission){
                    if($commission->is_package == 1){
                        $commission_detail = CommissionDetail::where('commission_id', $commission->id )->where('plan_id', getPlanWithAmount($user->id, $package->plan->price)->plan_id)->first();
                    }
                    else{
                        $commission_detail = $commission->commissionDetail[0];
                    }
                }
                updateCommissionWithLimit($user->id, $data->amount, $commission->wallet_id, $data->commission_id, $details, $commission_detail->commission_limit, $trx);
            }
        }

        CronUpdate::create([
            'user_id' => $user->id,
            'type' => 'purchased_plan',
            'amount' => $package->plan->price,
            'details' => $details,
            'status' => 0,
        ]);

        return redirect()->route('user.home')->withNotify($notify);
    
    }

    function roiCompound(Request $request)
    {
        // $general = GeneralSetting::first();
        // $release_date = $general->bal_trans_per_charge;
        // $current_date = Carbon::now();
        // $weekdays = Carbon::getDays();
        
        // $roi = $request->compounding;
        // $trx = $request->trx;
        

        $notify[] = roiReturn(Auth::id(), $request->compounding, $request->trx,$request->plan_purchase);
        
        return redirect()->route('user.home')->withNotify($notify);
    }


    public function binaryCom()
    {
        $data['page_title'] = "Binary Commission";
        $data['logs'] = Transaction::where('user_id', auth()->id())->where('remark', 'binary_commission')->orderBy('id', 'DESC')->paginate(config('constants.table.default'));
        $data['empty_message'] = 'No data found';
        return view($this->activeTemplate . '.user.transactions', $data);
    }

    public function binarySummery()
    {
        $data['page_title'] = "Binary Summery";
        $data['logs'] = UserExtra::where('user_id', auth()->id())->firstOrFail();
        return view($this->activeTemplate . '.user.binarySummery', $data);
    }

    public function bvlog(Request $request)
    {

        if ($request->type) {
            if ($request->type == 'leftBV') {
                $data['page_title'] = "Left BV";
                $data['logs'] = BvLog::where('user_id', auth()->id())->where('position', 1)->where('trx_type', '+')->orderBy('id', 'desc')->get();
            } elseif ($request->type == 'rightBV') {
                $data['page_title'] = "Right BV";
                $data['logs'] = BvLog::where('user_id', auth()->id())->where('position', 2)->where('trx_type', '+')->orderBy('id', 'desc')->get();
            } elseif ($request->type == 'cutBV') {
                $data['page_title'] = "Cut BV";
                $data['logs'] = BvLog::where('user_id', auth()->id())->where('trx_type', '-')->orderBy('id', 'desc')->get();
            } else {
                $data['page_title'] = "All Paid BV";
                $data['logs'] = BvLog::where('user_id', auth()->id())->where('trx_type', '+')->orderBy('id', 'desc')->get();
            }
        } else {
            $data['page_title'] = "Business Volume";
            $data['logs'] = BvLog::where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        }
        $data['summery'] = UserExtra::where('user_id', auth()->id())->first();
        $data['empty_message'] = 'No data found';
        return view($this->activeTemplate . '.user.bvLog', $data);
    }

    public function myRefLog()
    {
        $data['page_title'] = "Direct Team";
        $data['empty_message'] = 'No data found';
        $data['logs'] = UserFamily::where('user_id', auth()->id())->get();
        return view($this->activeTemplate . '.user.myRef', $data);
    }

    public function myTree()
    {
        $data['tree'] = showTreePage(Auth::id());
        $data['page_title'] = "My Tree";
        return view($this->activeTemplate . 'user.myTree', $data);
    }


    public function otherTree(Request $request, $username = null)
    {
        if ($request->username) {
            $user = User::where('username', $request->username)->first();
        } else {
            $user = User::where('username', $username)->first();
        }
        if ($user && treeAuth($user->id, auth()->id())) {
            $data['tree'] = showTreePage($user->id);
            $data['page_title'] = "Tree of " . $user->username;
            return view($this->activeTemplate . 'user.myTree', $data);
        }

        $notify[] = ['error', 'Tree Not Found or You do not have Permission to view that!!'];
        return redirect()->route('user.my.tree')->withNotify($notify);

    }
    
    function mediaIndex()
    {
        $data['page_title'] = "Media";
        $data['medias'] = Media::all();

        return view($this->activeTemplate . '.user.media', $data);

    }

    public function roi(Request $request){
        $data['page_title'] = "ROI Operations";
        $data['myPLans'] = PurchasedPlan::where('user_id', auth()->id())->get();
        $data['transactions'] = Transaction::where(['commission_id' => 1, 'user_id' => Auth::id()])->orderBy('created_at', 'desc')->get();

        return view($this->activeTemplate . '.user.roi.list', $data);
    }

    public function roiPlanDetails(Request $request){
        $plan = PurchasedPlan::with('plan')->where('user_id', auth()->id())->where('id',$request->planId)->first();
        $endRangePer = unserialize($plan->plan->features)[0];
        preg_match('/([\d\.]+)%/', $endRangePer, $matches);
        $dailyIncome = isset($matches[1]) ? floatval($matches[1]) : 0.0;
        $randomIncome = $dailyIncome;

        $currentTime = Carbon::now();
        $createPlan = Carbon::parse($plan->created_at)->format('y-m-d H:i');
        $timeDiff = $currentTime->diffInHours($createPlan);
        if ($timeDiff >= 24) {
            $planStartHours = 1;
        } else {
            $planStartHours = 0;
        }

        $roi_status = Transaction::where(['commission_id' => 1, 'user_id' => Auth::id(), 'plan_trx' => $plan->trx])
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->count();

        $data = [
            'price' => $plan->amount,
            'percentage' => $randomIncome,
            'trx' => $plan->trx,
            'roi_status' => $roi_status,
            'plan_roi' => $plan->is_roi,
            'planStartHours' => $planStartHours,
            'type' => $plan->type,
            'plan_points' => $plan->with_point,
        ];
        return $data;
    }

}
