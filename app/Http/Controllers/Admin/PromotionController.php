<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\User;

class PromotionController extends Controller
{
    public function userPromotion(Request $request){
        $page_title = 'User Promotions';
        $empty_message = 'No promotions yet.';
        $promotions = Promotion::all();
        $users = User::all();
        $info = json_decode(json_encode(getIpInfo()), true);
        $country = @implode(',', $info['country']);
        return view('admin.promotions.index',compact('page_title','empty_message','promotions','users','country'));
    }
    public function saveUserPromotion(Request $request){
        $this->validate($request, [
            'name'                      => 'required',
            'image'                     => 'mimes:png,jpg,jpeg,svg'
        ]);
        
        $fileimage = '';
        $user_id = '';
        $country = '';

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
            $location = 'assets/images/promotions/';

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

            $fileimage = $filename;
        }

        if ($request->promotion_for == 'user') {
            $user_id=$request->user_id;
        }
        else if($request->promotion_for == 'country'){
            $country=$request->country;
        }

        if ($request->status == 'on') {
            $status='1';    
        }
        else{
            $status='0';
        }

        $data = [
            'name' => $request->name,
            'detail' => $request->detail,
            'start' => $request->start_date,
            'end' => $request->end_date,
            'user_id' => json_encode($user_id),
            'image' => $fileimage,
            'status' => $status,
            'country' => json_encode($country) ,
        ];
        Promotion::create($data);
        return redirect()->route('admin.promotion.index')->with('message','Created Successfully');
    }

    public function deleteUserPromotion(Request $request){
        Promotion::where('id',$request->subscriber)->update(['status'=>'0']);
        return redirect()->route('admin.promotion.index')->with('message','Deleted Successfully');
    }
}
