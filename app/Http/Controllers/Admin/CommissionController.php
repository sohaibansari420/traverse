<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionDetail;
use App\Models\CommissionRelease;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Null_;

class CommissionController extends Controller
{
    public function commission()
    {
        $page_title = 'Commission';
        $empty_message = 'No Commission found';
        $commissions = Commission::paginate(getPaginate());
        $wallets = Wallet::where('status', 1)->get();
        $releases = CommissionRelease::where('status', 1)->get();
        return view('admin.commission.index', compact('page_title', 'commissions', 'wallets', 'releases', 'empty_message'));
    }

    public function commissionStore(Request $request)
    {
        $this->validate($request, [
            'name'                      => 'required',
            'wallet_id'                 => 'required',
            'commission_release_id'     => 'required',
            'image'                     => 'mimes:png,jpg,jpeg,svg'
        ]);

        $commission = new Commission();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/commission/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg' || $file_type = 'mage/svg+xml'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $size = "200x200";
                $image = Image::make($file);
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
                $image->save($location . '/' . $filename);                
            }

            $commission->image = $filename;
        }

        $commission->name                       = $request->name;
        $commission->icon                       = $request->icon;
        $commission->wallet_id                  = $request->wallet_id;
        $commission->commission_release_id      = $request->commission_release_id;
        $commission->is_compounding             = $request->is_compounding?1:0;
        $commission->is_package                 = $request->is_package?1:0;
        $commission->status                     = $request->status?1:0;
        $commission->save();

        $notify[] = ['success', 'New Commission created successfully'];
        return back()->withNotify($notify);
    }


    public function commissionUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                        => 'required',
            'name'                      => 'required',
            'wallet_id'                 => 'required',
            'commission_release_id'     => 'required',
            'image'                     => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $commission = Commission::find($request->id);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/commission/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg' || $file_type = 'mage/svg+xml'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $size = "200x200";
                $image = Image::make($file);
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
                $image->save($location . '/' . $filename);                
            }

            $commission->image = $filename;
        }

        $commission->name                       = $request->name;
        $commission->icon                       = $request->icon;
        $commission->wallet_id                  = $request->wallet_id;
        $commission->commission_release_id      = $request->commission_release_id;
        $commission->is_compounding             = $request->is_compounding?1:0;
        $commission->is_package                 = $request->is_package?1:0;
        $commission->status                     = $request->status?1:0;
        $commission->save();

        $notify[] = ['success', 'Commission Updated Successfully.'];
        return back()->withNotify($notify);
    }

    public function commissionDetail()
    {
        $page_title = 'Commission Detail';
        $empty_message = 'No Commission Detail found';
        $commissionDetail = CommissionDetail::paginate(getPaginate());
        $commissions = Commission::where('status', 1)->get();
        $plans = Plan::where('status', 1)->get();
        return view('admin.commission.detail', compact('page_title', 'commissionDetail', 'commissions', 'plans', 'empty_message'));
    }

    public function commissionDetailStore(Request $request)
    {
        $this->validate($request, [
            'commission_id'             => 'required',
            'percent'                   => 'required',
        ]);

        $commission = new CommissionDetail();
        $commission->commission_id              = $request->commission_id;
        
        if($request->plan_id){
            $commission->plan_id                = $request->plan_id;
        }

        if($request->level){
            $commission->level                  = $request->level;
        }

        $commission->commission_limit           = $request->commission_limit;
        $commission->capping                    = $request->capping;
        $commission->percent                    = $request->percent;
        $commission->days                       = $request->days;
        $commission->direct                     = $request->direct;
        $commission->save();

        $notify[] = ['success', 'New Commission Detail created successfully'];
        return back()->withNotify($notify);
    }


    public function commissionDetailUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                        => 'required',
            'commission_id'             => 'required',
            'percent'                   => 'required',
        ]);

        $commission = CommissionDetail::find($request->id);
        $commission->commission_id              = $request->commission_id;
        
        if($request->plan_id){
            $commission->plan_id                = $request->plan_id;
        }
        else
        {
            $commission->plan_id                = NULL;
        }

        if($request->level){
            $commission->level                  = $request->level;
        }
        else
        {
            $commission->level                  = NULL;
        }

        $commission->commission_limit           = $request->commission_limit;
        $commission->capping                    = $request->capping;
        $commission->percent                    = $request->percent;
        $commission->days                       = $request->days;
        $commission->direct                     = $request->direct;
        $commission->save();

        $notify[] = ['success', 'Commission Detail Updated Successfully.'];
        return back()->withNotify($notify);
    }

    public function commissionDetailDelete(Request $request)
    {
        $id = $request->id;
        $commission = CommissionDetail::find($id);
        if ($commission) {
            $commission->delete();
            $notify[] = ['success', 'Commission Detail has been deleted.'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', 'Notice error.'];
        return back()->withNotify($notify);
    }

    public function commissionRelease()
    {
        $page_title = 'Commission Release';
        $empty_message = 'No Commission Release found';
        $commissions = CommissionRelease::paginate(getPaginate());
        $com = Commission::where('status', 1)->get();
        return view('admin.commission.release', compact('page_title', 'commissions', 'empty_message', 'com'));
    }

    public function commissionReleaseStore(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
        ]);

        $commission = new CommissionRelease();
        $commission->name             = $request->name;
        $commission->status           = $request->status?1:0;
        $commission->save();

        $notify[] = ['success', 'New Commission Release created successfully'];
        return back()->withNotify($notify);
    }


    public function commissionReleaseUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required',
            'name'              => 'required',
        ]);

        $commission                 = CommissionRelease::find($request->id);
        $commission->name             = $request->name;
        $commission->status           = $request->status?1:0;
        $commission->save();

        $notify[] = ['success', 'Commission Release Updated Successfully.'];
        return back()->withNotify($notify);
    }

    public function commissionReleaseRelease(Request $request)
    {
        $this->validate($request, [
            'username'            => 'required',
            'amount'              => 'required',
        ]);

        $commission_id = $request->commission_id;
        $username = $request->username;
        $amount = $request->amount;
        $general = GeneralSetting::first();

        $valid_user = User::where('username', $username)->count();

        if(!$valid_user){
            $notify[] = ['error', 'Invalid Username'];
            return back()->withNotify($notify);
        }

        $user = User::where('username', $username)->firstOrFail();
        $user_plan = getUserHigherPlan($user->id);

        if ($user_plan){
            $commission = Commission::where('status', 1)->where('id', $commission_id)->firstOrFail();
            if($commission->is_package == 1){
                $com = CommissionDetail::where('commission_id', $commission->id )->where('plan_id', $user_plan->plan_id)->first();
            }
            else{    
                $com = $commission->commissionDetail[0];
            }
            updateCommissionWithLimit($user->id, $amount, $com->commission->wallet_id, $commission_id, $general->sitename, $com->commission_limit, $user_plan->trx);
        }

        $notify[] = ['success', 'Commission Released successfully'];
        return back()->withNotify($notify);
    }
}
