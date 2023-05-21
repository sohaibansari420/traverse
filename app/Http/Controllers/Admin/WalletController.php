<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Wallet;
use App\Models\Epin;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\CronUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class WalletController extends Controller
{
    public function wallet()
    {
        $page_title = 'Wallets';
        $empty_message = 'No Wallet found';
        $wallets = Wallet::paginate(getPaginate());;
        return view('admin.wallet.index', compact('page_title', 'wallets', 'empty_message'));
    }

    public function walletStore(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'currency'          => 'required',
            'passive'           => 'required',
            'image'             => 'mimes:png,jpg,jpeg,svg'
        ]);

        $wallet = new Wallet();
        
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
            $location = 'assets/images/wallet/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg'){
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

            $wallet->image = $filename;
        }

        $wallet->name             = $request->name;
        $wallet->currency         = $request->currency;
        $wallet->passive          = $request->passive;
        $wallet->icon             = $request->icon;
        $wallet->deposit          = $request->deposit?1:0;
        $wallet->withdraw         = $request->withdraw?1:0;
        $wallet->transfer         = $request->transfer?1:0;
        $wallet->display          = $request->display?1:0;
        $wallet->status           = $request->status?1:0;
        $wallet->save();

        $WalletId= $wallet->id;

        $users = User::all();
        foreach($users as $user){
            $user_wallet = new UserWallet();
            $user_wallet->user_id         = $user->id; 
            $user_wallet->wallet_id       = $WalletId;
            $user_wallet->save();
        }

        $notify[] = ['success', 'New Wallet created successfully'];
        return back()->withNotify($notify);
    }


    public function walletUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required',
            'name'              => 'required',
            'currency'          => 'required',
            'passive'           => 'required',
            'image'             => 'mimes:png,jpg,jpeg,svg'
        ]);

        $wallet = Wallet::find($request->id);

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
            $location = 'assets/images/wallet/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg'){
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

            $wallet->image = $filename;
        }

        $wallet->name           = $request->name;
        $wallet->currency       = $request->currency;
        $wallet->passive        = $request->passive;
        $wallet->icon           = $request->icon;
        $wallet->deposit        = $request->deposit?1:0;
        $wallet->withdraw       = $request->withdraw?1:0;
        $wallet->transfer       = $request->transfer?1:0;
        $wallet->display        = $request->display?1:0;
        $wallet->status         = $request->status?1:0;
        $wallet->save();

        $users = User::all();
        $wallets = UserWallet::where('wallet_id', $wallet->id)->get();
        foreach($users as $user){
            foreach($wallets as $wallet){
                $wallet->status = $request->status?1:0;
                $wallet->update();
            }
        }

        $notify[] = ['success', 'Wallet Updated Successfully.'];
        return back()->withNotify($notify);
    }

    public function walletAdjust()
    {
        $users = User::all();
        $wallets = Wallet::all();
        foreach($users as $user){
            foreach($wallets as $wallet){
                $record = DB::table('user_wallets')->where('user_id', $user->id)->where('wallet_id', $wallet->id)->first();
                if(!$record){
                    $user_wallet = new UserWallet();
                    $user_wallet->user_id         = $user->id; 
                    $user_wallet->wallet_id       = $wallet->id;
                    $user_wallet->save();
                }
            }
        }

        $notify[] = ['success', 'Wallet Adjusted Successfully.'];
        return back()->withNotify($notify);
    }
    
    public function epins()
    {
        $page_title = 'Epin Approval';
        $empty_message = 'No Epin found';
        $epins = Epin::where('status', 2)->get();
        return view('admin.wallet.epin', compact('page_title', 'epins', 'empty_message'));
    }
    
    public function epinUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required'
        ]);

        $pin = Epin::find($request->id);
        $user = User::find($pin->updated_by);
        $trx = getTrx();
        
        if($request->status){
            $package = Plan::where('price', $pin->amount)->where('status', 1)->firstOrFail();
            
            $details = $user->username . ' Subscribed to ' . $package->name . ' plan with Epin: ' . $pin->epin;
        
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
                'auto_renew' => 1,
            ]);

            CronUpdate::create([
                'user_id' => $user->id,
                'type' => 'purchased_plan',
                'amount' => $package->price,
                'details' => $details,
                'status' => 0,
            ]);
        }


        $pin->status         = $request->status?1:3;
        $pin->save();


        $notify[] = ['success', 'Epin Updated Successfully.'];
        return back()->withNotify($notify);
    }
}
