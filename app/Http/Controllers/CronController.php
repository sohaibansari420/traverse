<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\CommissionDetail;
use App\Models\CronUpdate;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\Transaction;
use App\Models\UnprocessedData;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserFamily;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function cron($id)
    {
        //adjustLimit(10, 4);
        //updateCommissionWithLimit(3, 400, 2, 2, "Testing", 4, "A3SVMPMCNR5H");
        //flushWallets();
        //stormCommission(788);
        $gnl = GeneralSetting::first();
        $gnl->last_cron = Carbon::now()->toDateTimeString();
		$gnl->save();
		
        if($id == "5min"){
            $crons = CronUpdate::where('status', 0)->get();
            foreach($crons as $cron){
                $cron->status = 1;
                $cron->save();
                if($cron->type == 'purchased_plan'){
                    
                    $user_families = UserFamily::where('mem_id', $cron->user_id)->get();
                    foreach($user_families as $user_family){
                        if($user_family->plan_id < getPlanWithAmount($cron->user_id, $cron->amount)->plan_id){
                            $user_family->plan_id = getPlanWithAmount($cron->user_id, $cron->amount)->plan_id;
                            $user_family->created_at = Carbon::now();
                            $user_family->save();
                        }
                        checkBlocks($user_family->user_id);
                    }

                    $commissions = Commission::where('status', 1)->where('commission_release_id', 1)->get();
                    foreach($commissions as $commission){
                        if($commission->id == 2){
                            updateBV($cron->user_id, $cron->amount , $cron->details);
                        }
                        elseif($commission->id == 3){
                            $plan_id = getPlanWithAmount($cron->user_id, $cron->amount)->plan_id;
                            if($commission->is_package == 1){
                                $ref = CommissionDetail::where('commission_id', $commission->id )->where('plan_id', $plan_id)->first();
                                $percent = $ref->percent;
                                $limit = $ref->commission_limit;
                                referralCommission($cron->user_id, $commission->wallet_id, $percent, $commission->id, $commission->name, $limit, $plan_id);
                            }
                            else{
                                $percent = $commission->commissionDetail[0]->percent;
                                $limit = $commission->commissionDetail[0]->commission_limit;
                                referralCommission($cron->user_id, $commission->wallet_id, $percent, $commission->id, $commission->name, $limit, $plan_id);
                            }
                        }
                    }
                }
                if($cron->type == 'storm_plan'){
                    stormCommission($cron->user_id);
                }
                if($cron->type == 'new_register'){
                    familyTreeAdjust($cron->user_id);
                    if($gnl->promo_account == 1){
                        $id = $cron->user_id;
                        $plan = 5;
                        $send_bv = 0;
                        $send_roi = 1;
                        $res = 1;
                        $user = User::find($id);
                        $ref_id = getReferenceId($id);
                        $trx = getTrx();
                        
                        $roi_status = 1;
                        $point_status = 0;

                        $package = Plan::where('id', $plan)->where('status', 1)->firstOrFail();

                        $oldPlan = $user->plan_purchased;
                        $user->plan_purchased = 1;
                        $user->save();

                        $details = $user->username . ' Subscribed to ' . $package->name . ' plan';

                        $notify[] = updateWallet($user->id, $trx, 7, NULL, '+', getAmount($package->price), $details, 0, 'purchased_plan', NULL);

                        if ($oldPlan == 0){
                            updatePaidCount($user->id);
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

                        updateNoBV($user->id, $package->bv , $details);

                    }
                }
            }
            checkStatusNowPayments();
            //release3Blocks();
        }
        elseif($id == "24hours"){
            $crons = CronUpdate::where('status', 0)->get();
            foreach($crons as $cron){
                
                $cron->status = 1;
                //$cron->save();
            }
            rewardRelease();
            //checkPaidStatusDaily();
            //autoCompounding();
        }
        elseif($id == "unprocessed_data"){
            $unprocessedDatas = UnprocessedData::where('is_processed', 0)->get();

            foreach ($unprocessedDatas as $unprocessedData) {

                if (true) {
                    $unprocessedData->update(['is_processed' => 1]);

                    call_user_func_array($unprocessedData->method, json_decode($unprocessedData->data));
                }
            }
        }
        // elseif($id == "saturday"){
        //     $commissions = Commission::where('status', 1)->where('commission_release_id', 4)->get();
        //     foreach($commissions as $commission){
        //         if($commission->id == 4){
        //             if($commission->is_package == 1){
                        
        //             }
        //             else{
        //                 vipCommission(1);
        //             }
        //         }
        //     }
        // }
        // elseif($id == "sunday"){
            // vipCommissionSet();
            //UserExtrasAdjust();
            
            /*$posid = 186;
            $commission = Commission::where('id', 2)->first();
            
            $user_plan = getUserHigherPlan($posid);
    
            if($commission->is_package == 1){
                $binary = CommissionDetail::where('commission_id', $commission->id )->where('plan_id', $user_plan->plan_id)->first();
            }
            else{
                $binary = $commission->commissionDetail[0];
            }

            if($user_plan){

                $res = cutPoints($posid, $binary->capping,  $user_plan->plan->price, $binary->percent, $binary->commission->wallet_id, $binary->commission_limit, $user_plan->trx);
            }*/
            
        // }
    }
}
