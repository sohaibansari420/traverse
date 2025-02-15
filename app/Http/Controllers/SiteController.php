<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Subscriber;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\PurchasedPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class SiteController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }
    
    public function getUserData2(Request $request){
        $username = $request->username;
        $user = User::where('username', $username)->first();
        if($user){
            if(Auth::attempt(['username' => $request->username, 'password' => $request->password], false)){
                $plan = PurchasedPlan::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                if($plan){
                    return response()->json([
                        'success'=> true, 
                        'message'=> 'User '. $user->firstname . ' ' . $user->lastname . ' found with Plan ' . $plan->plan->name, 
                        'data'=> $user,
                        'status'=> 1
                    ]);
                }
                else{
                    return response()->json([
                        'success'=> true, 
                        'message'=> 'User '. $user->firstname . ' ' . $user->lastname . ' found with no Plan Purchased',
                        'data'=> $user,
                        'status'=> 3
                    ]);
                }
            }
            else{
                return response()->json([
                    'success'=> true, 
                    'message'=>'User Authentication Error', 
                    'status'=> 2
                ]);
            }
        }
        else{
            return response()->json([
                'success'=> true, 
                'message'=>'User Not Found', 
                'status'=> 0
            ]);
        }
    }
    
    public function getUserData(Request $request){
        $user = $this->checkUserExistInDB($request->username, $request->password);
        
        if($user){
            if(Auth::attempt(['username' => $request->username, 'password' => $request->password], false)){
                $plan = PurchasedPlan::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                if($plan){
                    return response()->json([
                        'success'=> true, 
                        'message'=> 'User '. $user->firstname . ' ' . $user->lastname . ' found with Plan ' . $plan->plan->name, 
                        'data'=> $user,
                        'status'=> 1
                    ]);
                }
                else{
                    return response()->json([
                        'success'=> true, 
                        'message'=> 'User '. $user->firstname . ' ' . $user->lastname . ' found with no Plan Purchased',
                        'data'=> $user,
                        'status'=> 3
                    ]);
                }
            }
            else{
                return response()->json([
                    'success'=> true, 
                    'message'=>'User Authentication Error', 
                    'status'=> 2
                ]);
            }
        }
        else{
            return response()->json([
                'success'=> true, 
                'message'=>'User Not Found', 
                'status'=> 0
            ]);
        }
    }
    
    public function checkUserExistInDB($username, $password){
        
        Config::set('database.default', 'mysql');
        $user_count1 = User::where('username', $username)->count();
        
        Config::set('database.default', 'mysql2');
        $user_count2 = User::where('username', $username)->count();
        
        Config::set('database.default', 'mysql3');
        $user_count3 = User::where('username', $username)->count();
        
        Config::set('database.default', 'mysql4');
        $user_count4 = User::where('username', $username)->count();
        
        for( $user_counts = 4 ; $user_counts > 0 ; $user_counts--){
            Config::set('database.default', 'mysql' . $user_counts);
            if(Auth::attempt(['username' => $username, 'password' => $password], false)){
                $databaseName = Config::get('database.default');
                Cookie::queue('default_db', $databaseName, 3600);
                return User::where('username', $username)->first();
            }
        }
        
        if($user_count1 > 0){
            Config::set('database.default', 'mysql');
            return User::where('username', $username)->first();
        }
        elseif($user_count2 > 0){
            Config::set('database.default', 'mysql2');
            return User::where('username', $username)->first();
        }
        elseif($user_count3 > 0){
            Config::set('database.default', 'mysql3');
            return User::where('username', $username)->first();
        }
        elseif($user_count4 > 0){
            Config::set('database.default', 'mysql4');
            return User::where('username', $username)->first();
        }
    }

    public function CheckUsername(Request $request)
    {
        $id = User::where('username', $request->ref_id)->first();
        if ($id == '') {
            return response()->json(['success' => false, 'msg' => "<span class='help-block'><strong class='text-danger'>Referrer username not found</strong></span>"]);
        } else {
            return response()->json(['success' => true, 'msg' => "<span class='help-block'><strong class='text-success'>Referrer username matched</strong></span>
                     <input type='hidden' id='referrer_id' value='$id->id' name='referrer_id'>"]);

        }
    }


    public function userPosition(Request $request)
    {

        if (!$request->referrer) {
            return response()->json(['success' => false, 'msg' => "<span class='help-block'><strong class='text-danger'>Inter Referral name first</strong></span>"]);
        }
        if (!$request->position) {
            return response()->json(['success' => false, 'msg' => "<span class='help-block'><strong class='text-danger'>Select your position*</strong></span>"]);
        }
        $user = User::find($request->referrer);
        $pos = getPosition($user->id, $request->position);
        $join_under = User::find($pos['pos_id']);
        if ($pos['position'] == 1)
            $position = 'Left';
        else {
            $position = 'Right';
        }
        return response()->json(['success' => true, 'msg' => "<span class='help-block'><strong class='text-success'>Your are joining under $join_under->username at $position  </strong></span>"]);
    }

    public function index(){
        $count = Page::where('tempname',$this->activeTemplate)->where('slug','home')->count();
        if($count == 0){
            $page = new Page();
            $page->tempname = $this->activeTemplate;
            $page->name = 'HOME';
            $page->slug = 'home';
            $page->save();
        }

        $data['page_title'] = 'Home';
        $data['sections'] = Page::where('tempname',$this->activeTemplate)->where('slug','home')->firstOrFail();
        return view($this->activeTemplate . 'home', $data);
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $data['page_title'] = $page->name;
        $data['sections'] = $page;
        return view($this->activeTemplate . 'pages', $data);
    }


    public function contact()
    {
        $data['page_title'] = "Contact Us";
        $data['contact'] = Frontend::where('data_keys', 'contact_us.content')->first();
        return view($this->activeTemplate . 'contact', $data);
    }



    public function contactSubmit(Request $request)
    {
        $ticket = new SupportTicket();
        $message = new SupportMessage();

        $imgs = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'attachments' => [
                'sometimes',
                'max:4096',
                function ($attribute, $value, $fail) use ($imgs, $allowedExts) {
                    foreach ($imgs as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            return $fail("Images MAX  2MB ALLOW!");
                        }
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, pdf images are allowed");
                        }
                    }
                    if (count($imgs) > 5) {
                        return $fail("Maximum 5 images can be uploaded");
                    }
                },
            ],
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();

        $ticket->user_id = auth()->id();
        $ticket->name = $request->name;
        $ticket->email = $request->email;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->id() ? auth()->id() : 0;
        $adminNotification->title = 'New support ticket has opened';
        $adminNotification->click_url = route('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $path = imagePath()['ticket']['path'];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $image) {
                try {
                    $attachment = new SupportAttachment();
                    $attachment->support_message_id = $message->id;
                    $attachment->image = uploadImage($image, $path);
                    $attachment->save();

                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your ' . $image];
                    return back()->withNotify($notify)->withInput();
                }

            }
        }
        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blog()
    {
        $data['page_title'] = "Blog";
        $data['blogs'] = Frontend::where('data_keys', 'blog.element')->latest()->paginate(12);
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->firstOrFail();
        $data['page_title'] = $page->name;
        $data['sections'] = $page;
        return view(activeTemplate() . 'blog', $data);
    }

    public function singleBlog($slug, $id)
    {
        $data['blog'] = Frontend::where('data_keys', 'blog.element')->where('id', $id)->firstOrFail();
        $data['latestBlogs'] = Frontend::where('id', '!=', $id)->where('data_keys', 'blog.element')->take(5)->get();
        $data['page_title'] = "Details";
        return view(activeTemplate() . 'blogDetails', $data);
    }

    public function placeholderImage($size = null){
        if ($size != 'undefined') {
            $size = $size;
            $imgWidth = explode('x',$size)[0];
            $imgHeight = explode('x',$size)[1];
            $text = $imgWidth . '×' . $imgHeight;
        }else{
            $imgWidth = 150;
            $imgHeight = 150;
            $text = 'Undefined Size';
        }
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function subscriberStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191|unique:subscribers',
            'name'  => 'nullable|string|max:191',
        ]);

        Subscriber::create($request->only('email', 'name'));
        $notify[] = ['success', 'Subscribed Successfully!'];
        return back()->withNotify($notify);

    }

    public function terms()
    {
        $data['page_title'] = "Terms & Conditions";
        $data['terms'] = Frontend::where('data_keys', 'terms_conditions.content')->first();
        return view(activeTemplate() . 'terms', $data);
    }


}
