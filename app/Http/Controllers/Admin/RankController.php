<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class RankController extends Controller
{
    public function ranks()
    {
        $page_title = 'Ranks';
        $empty_message = 'No Rank found';
        $ranks = Rank::paginate(getPaginate());
        return view('admin.ranks.index', compact('page_title', 'ranks', 'empty_message'));
    }

    public function rankStore(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'points'        => 'required',
            'reward'        => 'required',
            'value'         => 'required',
            'image'         => 'mimes:png,jpg,jpeg,svg,gif',
            'reward_image'  => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $rank = new Rank();

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
            $location = 'assets/images/rank/';

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

            $rank->image = $filename;
        }

        if ($request->hasFile('reward_image')) {

            $file = $request->file('reward_image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/rank/reward/';

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

            $rank->reward_image = $filename;
        }

        $rank->name          = $request->name;
        $rank->points        = $request->points;
        $rank->reward        = $request->reward;
        $rank->value         = $request->value;
        $rank->save();

        $notify[] = ['success', 'New Rank created successfully'];
        return back()->withNotify($notify);
    }

    public function rankUpdate(Request $request)
    {
        $this->validate($request, [
            'id'            => 'required',
            'name'          => 'required',
            'points'        => 'required',
            'reward'        => 'required',
            'value'         => 'required',
            'image'         => 'mimes:png,jpg,jpeg,svg,gif',
            'reward_image'  => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $rank = Rank::find($request->id);

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
            $location = 'assets/images/rank/';

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

            $rank->image = $filename;
        }

        if ($request->hasFile('reward_image')) {

            $file = $request->file('reward_image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/rank/reward/';

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

            $rank->reward_image = $filename;
        }

        $rank->name          = $request->name;
        $rank->points        = $request->points;
        $rank->reward        = $request->reward;
        $rank->value         = $request->value;
        $rank->save();

        $notify[] = ['success', 'Rank Updated Successfully.'];
        return back()->withNotify($notify);
    }
}
