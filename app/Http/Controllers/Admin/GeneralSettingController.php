<?php

namespace App\Http\Controllers\Admin;

use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Image;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $general = GeneralSetting::first();
        $page_title = 'General Settings';
        return view('admin.setting.general_setting', compact('page_title', 'general'));
    }

    public function update(Request $request)
    {
        $validation_rule = [
            'base_color' => ['nullable', 'regex:/^[a-f0-9]{6}$/i'],
            'secondary_color' => ['nullable', 'regex:/^[a-f0-9]{6}$/i'],
        ];

        $validator = Validator::make($request->all(), $validation_rule, []);
        $validator->validate();

        $general_setting = GeneralSetting::first();
        $request->merge(['ev' => isset($request->ev) ? 1 : 0]);
        $request->merge(['en' => isset($request->en) ? 1 : 0]);
        $request->merge(['sv' => isset($request->sv) ? 1 : 0]);
        $request->merge(['sn' => isset($request->sn) ? 1 : 0]);
        $request->merge(['force_ssl' => isset($request->force_ssl) ? 1 : 0]);
        $request->merge(['secure_password' => isset($request->secure_password) ? 1 : 0]);
        $request->merge(['registration' => isset($request->registration) ? 1 : 0]);
        $request->merge(['manual_top_user' => isset($request->manual_top_user) ? 1 : 0]);
        $request->merge(['promo_account' => isset($request->promo_account) ? 1 : 0]);

        $general_setting->update($request->only(['sitename', 'cur_text', 'cur_sym', 'ev', 'en', 'sv', 'sn', 'force_ssl', 'secure_password', 'registration', 'manual_top_user', 'base_color', 'secondary_color', 'bal_trans_per_charge', 'bal_trans_fixed_charge', 'user1_detail', 'user2_detail', 'user3_detail', 'user1_direct', 'user2_direct', 'user3_direct', 'promo_account']));
        $notify[] = ['success', 'General Setting has been updated.'];
        return back()->withNotify($notify);
    }


    public function logoIcon()
    {
        $page_title = 'Logo & Icon';
        return view('admin.setting.logo_icon', compact('page_title'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo' => 'image|mimes:jpg,jpeg,png',
            'favicon' => 'image|mimes:png',
        ]);
        if ($request->hasFile('logo')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Logo could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('light_logo')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->light_logo)->save($path . '/light_logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Logo could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', imagePath()['favicon']['size']);
                Image::make($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Favicon could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo Icons has been updated.'];
        return back()->withNotify($notify);
    }

    /*public function planStore(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'description'       => 'required',
            'roi'               => 'required|numeric|min:0',
            'roi_limit'         => 'required|numeric|min:0',
            'network_limit'     => 'required|numeric|min:0',
            'capping'           => 'required|numeric|min:0',
            'price'             => 'required|numeric|min:0',
            'bv'                => 'required|min:0|integer',
            'ref_com'           => 'required|numeric|min:0',
            'tree_com'          => 'required|numeric|min:0',
        ]);


        $plan = new Plan();
        $plan->name             = $request->name;
        $plan->price            = $request->price;
        $plan->description      = $request->description;
        $plan->roi              = $request->roi;
        $plan->roi_limit        = $request->roi_limit;
        $plan->network_limit    = $request->network_limit;
        $plan->capping          = $request->capping;
        $plan->bv               = $request->bv;
        $plan->ref_com          = $request->ref_com;
        $plan->tree_com         = $request->tree_com;
        $plan->status           = $request->status?1:0;
        $plan->save();

        $notify[] = ['success', 'New Plan created successfully'];
        return back()->withNotify($notify);
    }*/

    public function noticeIndex()
    {

        $page_title = 'Notice Settings';
        $empty_message = 'No Notification found';
        $notifications = Notification::paginate(getPaginate());
        return view('admin.notice', compact('page_title', 'notifications', 'empty_message'));

    }

    public function noticeUpdate(Request $request)
    {

        $general_setting = GeneralSetting::first();
        $general_setting->notice = $request->notice;
        $general_setting->free_user_notice = $request->free_user_notice;
        $general_setting->save();

        $notify[] = ['success', 'Notice has been updated.'];
        return back()->withNotify($notify);

    }

    public function notificationStore(Request $r)
    {
        $r->validate([
            'title' => 'required',
            'type' => 'required',
            'detail' => 'required',
            'till_date' => 'required',
            'image' => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $username = 0;
        $data = User::where('username', $r->username)->first();
        if ($data != '') {

            $username = $data->id;
            $count = User::where('id', $username)->count();
            if ($count == 0) {
                return redirect()->back()->with('alert', 'Opps, No user found');
            }
        }

        if ($r->hasFile('image')) {
            $image = $r->file('image');
            $filename = 'notification' . rand() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/notifications/' . $filename;
            Image::make($image)->save($location);

            $input = [
                'user_id' => $username,
                'title' => $r->post('title'),
                'type' => $r->post('type'),
                'show_type' => $r->post('show_type'),
                'detail' => $r->post('detail'),
                'country' => $r->post('country'),
                'till_date' => date('Y-m-d', strtotime($r->post('till_date'))),
                'image' => $filename
            ];
        } else {
            $input = [
                'user_id' => $username,
                'title' => $r->post('title'),
                'type' => $r->post('type'),
                'show_type' => $r->post('show_type'),
                'detail' => $r->post('detail'),
                'country' => $r->post('country'),
                'till_date' => date('Y-m-d', strtotime($r->post('till_date')))
            ];
        }

        $notification = Notification::create($input);

        $notify[] = ['success', 'Notice has been stored.'];
        return back()->withNotify($notify);
    }

    public function notificationDelete($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->delete();
            $notify[] = ['success', 'Notice has been deleted.'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', 'Notice error.'];
        return back()->withNotify($notify);
    }

    public function depositIndex(Request $request){
        $config = Deposit::first();
        $page_title = 'Deposit Configuration';
        return view('admin.deposit.config', compact('page_title', 'config'));
    }

    public function depositUpdate(Request $request){
        $data = [
            'btc_wallet' => $request->btc_wallet
        ]; 
        Deposit::where('btc_wallet',$request->old_btc)->update($data);
        
        $notify[] = ['success', 'Wallet has been updated.'];
        return back()->withNotify($notify);
    }
}
