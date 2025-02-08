<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\CronUpdate;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserLogin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

        $this->activeTemplate = activeTemplate();
    }



    public function showRegistrationForm(Request $request)
    {
        $content = Frontend::where('data_keys', 'sign_up.content')->first();
        $info = json_decode(json_encode(getIpInfo()), true);

        if ($info['code'] == null) {
            $notify[] = ['error', 'Please wait a while.'];
            if ($request->ref && $request->position) {
                return redirect()->route('user.register' , ['ref'=>$request->ref , 'position'=> $request->position])->withNotify($notify);
            }
            else{
                return redirect()->route('user.register')->withNotify($notify);
            }
        }

        $country_code = @implode(',', $info['code']);

        if ($request->ref && $request->position) {
            $ref_user = User::where('username', $request->ref)->first();
            if ($ref_user == null) {
                $notify[] = ['error', 'Invalid Referral link.'];
                return redirect()->route('home')->withNotify($notify);
            }

            if ($request->position == 'left') {

                $position = 1;
            } elseif ($request->position == 'right') {
                $position = 2;
            } else {
                $notify[] = ['error', 'Invalid referral position'];
                return redirect()->route('home')->withNotify($notify);
            }

            $pos = getPosition($ref_user->id, $position);

            $join_under = User::find($pos['pos_id']);

            if ($pos['position'] == 1)
                $get_position = 'Left';

            else {
                $get_position = 'Right';
            }

            $joining = "<span class='help-block2'><strong class='custom-green' >Your are joining under $join_under->username at $get_position  </strong></span>";

            $page_title = "Sign Up";
            return view($this->activeTemplate . 'user.auth.register', compact('page_title', 'ref_user', 'joining', 'content',  'position', 'country_code'));

        }
        else{
            $ref_user = NULL;

            $position = 0;

            //$pos = getPosition($ref_user->id, $position);

            //$join_under = User::find($pos['pos_id']);

            //if ($pos['position'] == 1)
              //  $get_position = '';

            //else {
              //  $get_position = '';
            //}

            $joining = "";

            $page_title = "Sign Up";
            
            return view($this->activeTemplate . 'user.auth.register', compact('page_title', 'ref_user', 'joining', 'content',  'position', 'country_code'));
        }

        $ref_user = null;
        $joining = null;
        $page_title = "Sign Up";
        return view($this->activeTemplate . 'user.auth.register', compact('page_title', 'ref_user', 'content', 'country_code'));

    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $validate = Validator::make($data, [
            'referral'      => 'required|string|max:160',
            'position'      => 'required|integer',
            'firstname'     => 'sometimes|required|string|max:60',
            'lastname'      => 'sometimes|required|string|max:60',
            'email'         => 'required|string|email|max:160|unique:users',
            // 'legacy_email'  => 'required|string|email|max:160|unique:users',
            'mobile'        => 'required|string|max:30',
            'password'      => 'required|string|min:6|confirmed',
            'username'      => 'required|alpha_num|unique:users|min:3',
            'captcha'       => 'sometimes|required',
            'country_code'  => 'required'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $general = GeneralSetting::first();

        if ($general->secure_password) {
            $notify = $this->strongPassCheck($request->password);
            if ($notify) {
                return back()->withNotify($notify)->withInput($request->all());
            }
        }

        $exist = User::where('mobile',$request->country_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'Mobile number already exist'];
            return back()->withNotify($notify)->withInput();
        }

        $userCheck = User::where('username', $request->referral)->first();

        if (!$userCheck)
        {
            $notify[] = ['error', 'Referral not found.'];
            return back()->withNotify($notify);
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid Captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $gnl = GeneralSetting::first();

        $userCheck = User::where('username', $data['referral'])->first();

        //User Create
        $user = new User();
        $user->ref_id       = $userCheck->id;
        $user->position     = $data['position'];
        $user->firstname    = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname     = isset($data['lastname']) ? $data['lastname'] : null;
        $user->email        = strtolower(trim($data['email']));
        // $user->legacy_email = strtolower(trim($data['legacy_email']));
        $user->password     = Hash::make($data['password']);
        $user->username     = trim($data['username']);
        $user->ref_id       = $userCheck->id;
        $user->image        = 'default.jpg';
        $user->mobile       = $data['country_code'].$data['mobile'];
        $user->address      = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : 'France',
            'city' => ''
        ];
        $user->status = 1;
        $user->ev = $gnl->ev ? 0 : 1;
        $user->sv = $gnl->sv ? 0 : 1;
        $user->ts = 0;
        $user->tv = 1;
        $user->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = route('admin.users.detail',$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->location =  $exist->location;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->location =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    protected function strongPassCheck($password){
        $password = $password;
        $capital = '/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/';
        $capital = preg_match($capital,$password);
        $notify = null;
        if (!$capital) {
            $notify[] = ['error','Minimum 1 capital word is required'];
        }
        $number = '/[123456790]/';
        $number = preg_match($number,$password);
        if (!$number) {
            $notify[] = ['error','Minimum 1 number is required'];
        }
        $special = '/[`!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?~\']/';
        $special = preg_match($special,$password);
        if (!$special) {
            $notify[] = ['error','Minimum 1 special character is required'];
        }
        return $notify;
    }

    public function registered(Request $request, $user)
    {
        createWallets($user->id);
        $ref_id = getReferenceId($user->id);
        $pos = getPosition($ref_id, $user->position);
        $user->pos_id = $pos['pos_id'];
        $user->save();
        $user_extras = new UserExtra();
        $user_extras->user_id = $user->id;
        $user_extras->save();
        updateFreeCount($user->id);
        CronUpdate::create([
            'user_id' => $user->id,
            'type' => 'new_register',
            'amount' => 0,
            'details' => "New User Registered",
            'status' => 0,
        ]);
        return redirect()->route('user.home');
    }

}
