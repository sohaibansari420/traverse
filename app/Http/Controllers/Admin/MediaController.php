<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    public function media()
    {
        $page_title = 'Media';
        $empty_message = 'No Media found';
        $medias = Media::paginate(getPaginate());
        return view('admin.media.index', compact('page_title', 'medias', 'empty_message'));
    }

    public function mediaStore(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'image'         => 'mimes:png,jpg,jpeg,svg,gif',
            'media'         => 'mimes:pdf',
        ]);

        $media = new Media();

        if ($request->hasFile('media')) {

            $file = $request->file('media');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            echo $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/media/files/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'application/pdf'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $notify[] = ['error', 'File Not Supported'];
                return back()->withNotify($notify);              
            }

            $media->media = $filename;
        }

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
            $location = 'assets/images/media/';

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

            $media->image = $filename;
        }

        $media->name          = $request->name;
        $media->save();

        $notify[] = ['success', 'New Media created successfully'];
        return back()->withNotify($notify);
    }

    public function mediaUpdate(Request $request)
    {
        $this->validate($request, [
            'id'            => 'required',
            'name'          => 'required',
            'image'         => 'mimes:png,jpg,jpeg,svg,gif',
            'media'         => 'mimes:pdf',
        ]);

        $media = Media::find($request->id);

        if ($request->hasFile('media')) {

            $file = $request->file('media');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            echo $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/media/files/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'application/pdf'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $notify[] = ['error', 'File Not Supported'];
                return back()->withNotify($notify);              
            }

            $media->media = $filename;
        }

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
            $location = 'assets/images/media/';

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

            $media->image = $filename;
        }

        $media->name          = $request->name;
        $media->save();
        

        $notify[] = ['success', 'Media Updated Successfully.'];
        return back()->withNotify($notify);
    }
}
