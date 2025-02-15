<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class MlmController extends Controller
{
    public function plan()
    {
        $page_title = 'MLM Packages';
        $empty_message = 'No Plan found';
        $plans = Plan::paginate(getPaginate());
        return view('admin.plan.index', compact('page_title', 'plans', 'empty_message'));
    }

    public function planStore(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'description'       => 'required',
            'features'          => 'required',
            'price'             => 'required|numeric|min:0',
            'bv'                => 'required|min:0|integer',
            'image'             => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $plan = new Plan();

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
            $location = 'assets/images/plan/';

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

            $plan->image = $filename;
        }

        $plan->name             = $request->name;
        $plan->title            = $request->title;
        $plan->price            = $request->price;
        $plan->description      = $request->description;
        $plan->features         = is_array($request->post('features')) ? serialize($request->post('features')) : $request->post('features');
        $plan->bv               = $request->bv;
        $plan->status           = $request->status?1:0;
        $plan->save();

        $notify[] = ['success', 'New Plan created successfully'];
        return back()->withNotify($notify);
    }


    public function planUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required',
            'name'              => 'required',
            'description'       => 'required',
            'features'          => 'required',
            'price'             => 'required|numeric|min:0',
            'bv'                => 'required|min:0|integer',
            'image'             => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $features = explode(",", $request->features);

        $plan = Plan::find($request->id);

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
            $location = 'assets/images/plan/';

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

            $plan->image = $filename;
        }

        $plan->name             = $request->name;
        $plan->price            = $request->price;
        $plan->description      = $request->description;
        $plan->features         = is_array($features) ? serialize($features) : $features;
        $plan->bv               = $request->bv;
        $plan->title            = $request->title;
        $plan->status           = $request->status?1:0;
        $plan->save();

        $notify[] = ['success', 'Plan Updated Successfully.'];
        return back()->withNotify($notify);
    }



    public function matchingUpdate(Request $request)
    {
        $this->validate($request, [
            'bv_price' => 'required|min:0',
            'total_bv' => 'required|min:0|integer',
            'max_bv' => 'required|min:0|integer',
        ]);

        $setting = GeneralSetting::first();

        if ($request->matching_bonus_time == 'daily') {
            $when = $request->daily_time;
        } elseif ($request->matching_bonus_time == 'weekly') {
            $when = $request->weekly_time;
        } elseif ($request->matching_bonus_time == 'monthly') {
            $when = $request->monthly_time;
        }


        $setting->bv_price = $request->bv_price;
        $setting->total_bv = $request->total_bv;
        $setting->max_bv = $request->max_bv;
        $setting->cary_flash = $request->cary_flash;
        $setting->matching_bonus_time = $request->matching_bonus_time;
        $setting->matching_when = $when;
        $setting->save();

        $notify[] = ['success', 'Matching bonus has been updated.'];
        return back()->withNotify($notify);

    }

}
