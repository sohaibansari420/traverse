<?php

use App\Http\Controllers\Gateway\PaymentController;
use App\Models\BvLog;
use App\Models\Commission;
use App\Models\CommissionDetail;
use App\Models\CronUpdate;
use App\Models\Deposit;
use App\Models\EmailTemplate;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Models\PurchasedPlan;
use App\Models\Rank;
use App\Models\RankAchiever;
use App\Models\SmsTemplate;
use App\Models\Transaction;
use App\Models\UnprocessedData;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\UserFamily;
use App\Models\UserNetwork;
use App\Models\UserWallet;
use App\Models\Wallet;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;


function sidebarVariation()
{

    /// for sidebar
    $variation['sidebar'] = 'bg_img';

    //for selector
    $variation['selector'] = 'capsule--rounded';
    //for overlay

    $variation['overlay'] = 'overlay--dark';
    //Opacity
    $variation['opacity'] = 'overlay--opacity-8'; // 1-10

    return $variation;

}

function systemDetails()
{
    $general = GeneralSetting::first();
    $system['name'] = $general->sitename;
    $system['version'] = '1.0';
    return $system;
}

function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}


function shortDescription($string, $length = 120)
{
    return Illuminate\Support\Str::limit($string, $length);
}

function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}


function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if (!empty($old)) {
        removeFile($location . '/' . $old);
        removeFile($location . '/thumb_' . $old);
    }
    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();

    $file_type = $file->getMimeType();

    if ($file_type == 'image/svg') {
        //Move Uploaded File
        $file->move($location, $filename);
    } else {
        $image = Image::make($file);
        if (!empty($size)) {
            $size = explode('x', strtolower($size));
            $image->resize($size[0], $size[1], function ($constraint) {
                $constraint->upsize();
            });
        }
        $image->save($location . '/' . $filename);

        if (!empty($thumb)) {

            $thumb = explode('x', $thumb);
            Image::make($file)->resize($thumb[0], $thumb[1], function ($constraint) {
                $constraint->upsize();
            })->save($location . '/thumb_' . $filename);
        }
    }

    return $filename;
}

function uploadFile($file, $location, $size = null, $old = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if (!empty($old)) {
        removeFile($location . '/' . $old);
    }

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $file->move($location, $filename);
    return $filename;
}

function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}


function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}


function activeTemplate($asset = false)
{
    $gs = GeneralSetting::first(['active_template']);
    $template = $gs->active_template;
    $sess = session()->get('template');
    if (trim($sess) != null) {
        $template = $sess;
    }
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $gs = GeneralSetting::first(['active_template']);
    $template = $gs->active_template;
    $sess = session()->get('template');
    if (trim($sess) != null) {
        $template = $sess;
    }
    return $template;
}

function reCaptcha()
{
    $reCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', 1)->first();
    return $reCaptcha ? $reCaptcha->generateScript() : '';
}

function analytics()
{
    $analytics = Extension::where('act', 'google-analytics')->where('status', 1)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function tawkto()
{
    $tawkto = Extension::where('act', 'tawk-chat')->where('status', 1)->first();
    return $tawkto ? $tawkto->generateScript() : '';
}

function fbcomment()
{
    $comment = Extension::where('act', 'fb-comment')->where('status', 1)->first();
    return $comment ? $comment->generateScript() : '';
}

function getCustomCaptcha($height = 46, $width = '300px', $bgcolor = '#003', $textcolor = '#abc')
{
    $textcolor = '#' . GeneralSetting::first()->base_color;
    $captcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    if ($captcha) {
        $code = rand(100000, 999999);
        $char = str_split($code);
        $ret = '<link href="https://fonts.googleapis.com/css?family=Henny+Penny&display=swap" rel="stylesheet">';
        $ret .= '<div style="height: ' . $height . 'px; line-height: ' . $height . 'px; width:' . $width . '; text-align: center; background-color: ' . $bgcolor . '; color: ' . $textcolor . '; font-size: ' . ($height - 20) . 'px; font-weight: bold; letter-spacing: 20px; font-family: \'Henny Penny\', cursive;  -webkit-user-select: none; -moz-user-select: none;-ms-user-select: none;user-select: none;  display: flex; justify-content: center;">';
        foreach ($char as $value) {
            $ret .= '<span style="    float:left;     -webkit-transform: rotate(' . rand(-60, 60) . 'deg);">' . $value . '</span>';
        }
        $ret .= '</div>';
        $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
        $ret .= '<input type="hidden" name="captcha_secret" value="' . $captchaSecret . '">';
        return $ret;
    } else {
        return false;
    }
}


function captchaVerify($code, $secret)
{
    $captcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    if ($captchaSecret == $secret) {
        return true;
    }
    return false;
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function getAmount($amount, $length = 0)
{
    if (0 < $length) {
        return round($amount + 0, $length);
    }
    return $amount + 0;
}

function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet, $amount, $crypto = null)
{

    $varb = $wallet . "?amount=" . $amount;
    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
}

//moveable
function curlContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//moveable
function curlPostContent($url, $arr = null)
{
    if ($arr) {
        $params = http_build_query($arr);
    } else {
        $params = '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curlGetContent($url, $header = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curlPostContentHeader($url, $params = null, $header = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function inputTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}


function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}


function str_slug($title = null)
{
    return \Illuminate\Support\Str::slug($title);
}

function str_limit($title = null, $length = 10)
{
    return \Illuminate\Support\Str::limit($title, $length);
}

//moveable
function getIpInfo()
{
    $ip = null;
    $deep_detect = TRUE;

    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }

    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');


    return $data;
}

//moveable
function osBrowser()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    $data['os_platform'] = $os_platform;
    $data['browser'] = $browser;

    return $data;
}

function siteName()
{
    $general = GeneralSetting::first();
    $sitname = str_word_count($general->sitename);
    $sitnameArr = explode(' ', $general->sitename);
    if ($sitname > 1) {
        $title = "<span>$sitnameArr[0] </span> " . str_replace($sitnameArr[0], '', $general->sitename);
    } else {
        $title = "<span>$general->sitename</span>";
    }

    return $title;
}


//moveable
function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = 'https://license.viserlab.com/updates/templates/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}


function getPageSections($arr = false)
{

    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}


function getImage($image, $size = null)
{
    $clean = '';
    $size = $size ? $size : 'undefined';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    } else {
        return route('placeholderImage', $size);
    }
}

function notify($user, $type, $shortCodes = null)
{

    sendEmail($user, $type, $shortCodes);
    sendSms($user, $type, $shortCodes);
}


/*SMS EMIL moveable*/

function sendSms($user, $type, $shortCodes = [])
{
    $general = GeneralSetting::first(['sn', 'sms_api']);
    $sms_template = SmsTemplate::where('act', $type)->where('sms_status', 1)->first();
    if ($general->sn == 1 && $sms_template) {

        $template = $sms_template->sms_body;

        foreach ($shortCodes as $code => $value) {
            $template = shortCodeReplacer('{{' . $code . '}}', $value, $template);
        }
        $template = urlencode($template);

        $message = shortCodeReplacer("{{number}}", $user->mobile, $general->sms_api);
        $message = shortCodeReplacer("{{message}}", $template, $message);
        $result = @curlContent($message);
    }
}

function sendEmail($user, $type = null, $shortCodes = [])
{
    $general = GeneralSetting::first();

    $email_template = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if ($general->en != 1 || !$email_template) {
        return;
    }

    $message = shortCodeReplacer("{{name}}", $user->username, $general->email_template);
    $message = shortCodeReplacer("{{message}}", $email_template->email_body, $message);

    if (empty($message)) {
        $message = $email_template->email_body;
    }

    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{' . $code . '}}', $value, $message);
    }
    $config = $general->mail_config;

    if ($config->name == 'php') {
        sendPhpMail($user->email, $user->username, $email_template->subj, $message);
    } else if ($config->name == 'smtp') {
        sendSmtpMail($config, $user->email, $user->username, $email_template->subj, $message, $general);
    } else if ($config->name == 'sendgrid') {
        sendSendGridMail($config, $user->email, $user->username, $email_template->subj, $message, $general);
    } else if ($config->name == 'mailjet') {
        sendMailjetMail($config, $user->email, $user->username, $email_template->subj, $message, $general);
    }
}


function sendPhpMail($receiver_email, $receiver_name, $subject, $html)
{
    $gnl = GeneralSetting::first();
    // $headers = "From: " . $gnl->sitename . " <" . $gnl->email_from . "> \r\n";
    // $headers .= "Reply-To: " . $gnl->sitename . " <" . $gnl->email_from . "> \r\n";
    // $headers .= "MIME-Version: 1.0\r\n";
    // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    // @mail($receiver_email, $subject, $message, $headers);

//    dd($receiver_email, $receiver_name, $subject, $html,$gnl->sitename);
//    dd(config('mail.mailers.smtp.username'));
    Mail::html($html, function ($message) use ($receiver_email, $subject, $gnl) {
        $message->to($receiver_email)
            ->from(config('mail.mailers.smtp.username'), $gnl->sitename)
            ->subject($subject);
    });

}


function sendSmtpMail($config, $receiver_email, $receiver_name, $subject, $message, $gnl)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = $config->host;
        $mail->SMTPAuth = true;
        $mail->Username = $config->username;
        $mail->Password = $config->password;
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port = $config->port;
        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom($gnl->email_from, $gnl->sitetitle);
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addReplyTo($gnl->email_from, $gnl->sitename);
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        throw new Exception($e);
    }
}


function sendSendGridMail($config, $receiver_email, $receiver_name, $subject, $message, $gnl)
{
    $sendgridMail = new \SendGrid\Mail\Mail();
    $sendgridMail->setFrom($gnl->email_from, $gnl->sitetitle);
    $sendgridMail->setSubject($subject);
    $sendgridMail->addTo($receiver_email, $receiver_name);
    $sendgridMail->addContent("text/html", $message);
    $sendgrid = new \SendGrid($config->appkey);
    try {
        $response = $sendgrid->send($sendgridMail);
    } catch (Exception $e) {
        // echo 'Caught exception: '. $e->getMessage() ."\n";
    }
}


function sendMailjetMail($config, $receiver_email, $receiver_name, $subject, $message, $gnl)
{
    $mj = new \Mailjet\Client($config->public_key, $config->secret_key, true, ['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $gnl->email_from,
                    'Name' => $gnl->sitetitle,
                ],
                'To' => [
                    [
                        'Email' => $receiver_email,
                        'Name' => $receiver_name,
                    ]
                ],
                'Subject' => $subject,
                'TextPart' => "",
                'HTMLPart' => $message,
            ]
        ]
    ];
    $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
}


function getPaginate($paginate = 20)
{
    return $paginate;
}


function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}


function imagePath()
{
    $db_name = Config::get('database.default');
    if ($db_name == "mysql2") {
        $location = 'myamano/assets/';
    } elseif ($db_name == "mysql3") {
        $location = 'amano3/assets/';
    } elseif ($db_name == "mysql4") {
        $location = 'amano4/assets/';
    } else {
        $location = 'assets/';
    }

    $data['gateway'] = [
        'path' => 'assets/images/gateway',
        'size' => '800x800',
    ];
    $data['verify'] = [
        'withdraw' => [
            'path' => 'assets/images/verify/withdraw'
        ],
        'deposit' => [
            'path' => 'assets/images/verify/deposit'
        ]
    ];
    $data['image'] = [
        'default' => 'assets/images/default.png',
    ];
    $data['withdraw'] = [
        'method' => [
            'path' => 'assets/images/withdraw/method',
            'size' => '800x800',
        ]
    ];
    $data['ticket'] = [
        'path' => $location . 'images/support',
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64'
    ];
    $data['logoIcon'] = [
        'path' => 'assets/images/logoIcon',
    ];
    $data['favicon'] = [
        'size' => '128x128',
    ];
    $data['extensions'] = [
        'path' => 'assets/images/extensions',
    ];
    $data['seo'] = [
        'path' => 'assets/images/seo',
        'size' => '600x315'
    ];
    $data['profile'] = [
        'user' => [
            'path' => 'assets/images/user/profile',
            'size' => '350x300'
        ],
        'admin' => [
            'path' => 'assets/admin/images/profile',
            'size' => '400x400'
        ]
    ];
    return $data;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'd M, Y h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

//moveable
function sendGeneralEmail($email, $subject, $message, $receiver_name = '')
{

    $general = GeneralSetting::first();

    if ($general->en != 1 || !$general->email_from) {
        return;
    }

    $message = shortCodeReplacer("{{message}}", $message, $general->email_template);
    $message = shortCodeReplacer("{{name}}", $receiver_name, $message);
    $config = $general->mail_config;

    if ($config->name == 'php') {
        sendPhpMail($email, $receiver_name, $general->email_from, $subject, $message);
    } else if ($config->name == 'smtp') {
        sendSmtpMail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    } else if ($config->name == 'sendgrid') {
        sendSendGridMail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    } else if ($config->name == 'mailjet') {
        sendMailjetMail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    }
}

function getContent($data_keys, $singleQuery = false, $limit = null, $orderById = false)
{
    if ($singleQuery) {
        $content = Frontend::where('data_keys', $data_keys)->latest()->first();
    } else {
        $article = Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $data_keys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $data_keys)->latest()->get();
        }
    }
    return $content;
}


function gatewayRedirectUrl()
{
    return 'user.deposit';
}

function paginateLinks($data, $design = 'admin.partials.paginate')
{
    return $data->appends(request()->all())->links($design);
}

function printEmail($email)
{
    $beforeAt = strstr($email, '@', true);
    $withStar = substr($beforeAt, 0, 2) . str_repeat("**", 5) . substr($beforeAt, -2) . strstr($email, '@');
    return $withStar;
}

/* MLM FUNCTION  */

function getUserById($id)
{
    return User::find($id);
}

function createBVLog($user_id, $lr, $amount, $details)
{
    $bvlog = new BvLog();
    $bvlog->user_id = $user_id;
    $bvlog->position = $lr;
    $bvlog->amount = $amount;
    $bvlog->trx_type = '-';
    $bvlog->details = $details;
    $bvlog->save();
}


function mlmWidth()
{
    return 2;
}

function mlmPositions()
{
    return array(
        '1' => 'Left',
        '2' => 'Right',
    );
}

function getPosition($parentid, $position)
{
    $childid = getTreeChildId($parentid, $position);

    if ($childid != "-1") {
        $id = $childid;
    } else {
        $id = $parentid;
    }
    while ($id != "" || $id != "0") {
        if (isUserExists($id)) {
            $nextchildid = getTreeChildId($id, $position);
            if ($nextchildid == "-1") {
                break;
            } else {
                $id = $nextchildid;
            }
        } else break;
    }

    $res['pos_id'] = $id;
    $res['position'] = $position;
    return $res;
}

function getTreeChildId($parentid, $position)
{
    $cou = User::where('pos_id', $parentid)->where('position', $position)->count();
    $cid = User::where('pos_id', $parentid)->where('position', $position)->first();
    if ($cou == 1) {
        return $cid->id;
    } else {
        return -1;
    }
}

function isUserExists($id)
{
    $user = User::find($id);
    if ($user) {
        return true;
    } else {
        return false;
    }
}

function getPositionId($id)
{
    $user = User::find($id);
    if ($user) {
        return $user->pos_id;
    } else {
        return 0;
    }
}

function getPositionLocation($id)
{
    $user = User::find($id);
    if ($user) {
        return $user->position;
    } else {
        return 0;
    }
}

function updateFreeCount($id)
{
    while ($id != "" || $id != "0") {
        if (isUserExists($id)) {
            $posid = getPositionId($id);
            if ($posid == "0") {
                break;
            }
            $position = getPositionLocation($id);

            $extra = UserExtra::where('user_id', $posid)->first();
            if (isset($extra)) {
                if ($position == 1) {
                    $extra->free_left += 1;
                } else {
                    $extra->free_right += 1;
                }
                $extra->save();
            }

            $id = $posid;

        } else {
            break;
        }
    }

}

function updatePaidCount($id)
{
    while ($id != "" || $id != "0") {
        if (isUserExists($id)) {
            $posid = getPositionId($id);
            if ($posid == "0") {
                break;
            }
            $position = getPositionLocation($id);

            $extra = UserExtra::where('user_id', $posid)->first();

            if ($position == 1) {
                $extra->free_left -= 1;
                $extra->paid_left += 1;
            } else {
                $extra->free_right -= 1;
                $extra->paid_right += 1;
            }
            $extra->save();
            $id = $posid;
        } else {
            break;
        }
    }

}

function roiReturn($user_id = '', $is_compounding = '', $trx = '')
{
    $commission = Commission::where('status', 1)->where('id', 1)->firstOrFail();
    $user = User::find($user_id);
    $user_plan = PurchasedPlan::where('trx', $trx)->firstOrFail();
    $roi = [];
    $roi_status = Transaction::where(['commission_id' => 1, 'user_id' => $user_id, 'plan_trx' => $trx])
        ->whereDate('created_at', Carbon::today())
        ->count();

    if ($user_plan->is_roi == 0) {
        return ['error', 'Daily is off for this Plan'];
    }

    if ($roi_status != 0) {
        return ['error', 'Already received the Daily for this Plan'];
    }

    if ($commission->is_package == 1) {
        $roi = CommissionDetail::where('commission_id', $commission->id)->where('plan_id', $user_plan->plan_id)->first();
    } else {
        $roi = $commission->commissionDetail[0];
    }

    if ($is_compounding == 0 || $is_compounding == 2) {
        $percent = $roi->percent;
        $network_limit = $roi->commission_limit;
        $weeks = $roi->days;
        $plan_price = $user_plan->amount;

        $amount = ($plan_price / 100) * $percent;

        if ($is_compounding == 2) {
            $user_plan->auto_compounding = 1;
        }

        $user_plan->roi_return += $amount;
        $user_plan->amount += $amount;
        $user_plan->save();

        $details = 'Compounding ' . getCommissionName(1) . ' From ' . $user_plan->plan->name . ' Plan';
        updateTransaction($user->id, getTrx(), NULL, 1, '+', getAmount($amount), $details, 0, 'compounding', $trx);

        DB::table('user_families')
            ->where('mem_id', $user->id)
            ->update([
                'weekly_roi' => DB::raw('weekly_roi + ' . $amount),
            ]);
    } else {

        $percent = $roi->percent / 5;
        $network_limit = $roi->commission_limit;
        $weeks = $roi->days;
        $plan_price = $user_plan->amount;

        $amount = ($plan_price / 100) * $percent;

        if ($user_plan->is_expired == 0) {
            updateCommissionWithLimit($user->id, $amount, $commission->wallet_id, $commission->id, $user_plan->plan->name . ' Plan', $network_limit, $trx);
        }

        if ($weeks >= $user_plan->roi_limit) {
            $user_plan->roi_limit += 1;
            $user_plan->roi_return += $amount;
            $user_plan->save();
        } else {
            // expire the package
            $user_plan->roi_limit += 1;
            $user_plan->roi_return += $amount;
            $user_plan->is_expired = 1;
            $user_plan->save();
        }

        DB::table('user_families')
            ->where('mem_id', $user->id)
            ->update([
                'weekly_roi' => DB::raw('weekly_roi + ' . $amount),
            ]);
    }

    return ['success', 'Daily Return Successfully'];
}

function cutPoints($posid = '', $capping = '', $plan_amount = '', $binary_per = '', $wallet_id = '', $network_limit = '', $plan_trx = '')
{
    $mem = UserExtra::where('user_id', $posid)->first();

    if ($mem->binary_active == 1) {

        $pack_cap = $plan_amount * $capping;
        $today_income = Transaction::whereRaw('user_id = ? and commission_id = ? and Date(created_at) = ?', [$posid, 2, Carbon::today()->toDateString()])->sum('amount');

        if ($mem->bv_left > $mem->bv_right & $mem->bv_right != 0) {
            $lp = $mem->bv_left - $mem->bv_right;
            $cut_points = $mem->bv_right;
            $cut_point = ($cut_points * $binary_per) / 100;
            $total_today = $today_income + $cut_point;
            if ($today_income < $pack_cap) {
                if ($total_today < $pack_cap) {
                    updateCommissionWithLimit($mem->user_id, $cut_point, $wallet_id, 2, 'Business Volume Matching', $network_limit, $plan_trx);
                    $mem->bv_right = 0;
                    $mem->bv_left = $lp;
                    $mem->save();
                } else {
                    $adjust_point = $pack_cap - $today_income;
                    $flush_point = $cut_point - $adjust_point;
                    updateCommissionWithLimit($mem->user_id, $adjust_point, $wallet_id, 2, 'Business Volume Matching', $network_limit, $plan_trx);

                    //update flused off
                    $details = 'Flushed-Off ' . getCommissionName(2) . ' Due to Capping';
                    updateWallet($mem->user_id, getTrx(), 7, 2, '+', getAmount($flush_point), $details, 0, str_replace(' ', '_', getCommissionName(2)), NULL);

                    $mem->bv_right = 0;
                    $mem->bv_left = $lp;
                    $mem->save();
                }
            } else {
                //update flused off
                $details = 'Flushed-Off ' . getCommissionName(2) . ' Due to Capping';
                updateWallet($mem->user_id, getTrx(), 7, 2, '+', getAmount($cut_point), $details, 0, str_replace(' ', '_', getCommissionName(2)), NULL);

                $mem->bv_right = 0;
                $mem->bv_left = $lp;
                $mem->save();
            }
            updateRankPoints($mem->user_id, $cut_points);
            return 1;
        } elseif ($mem->bv_left < $mem->bv_right & $mem->bv_left != 0) {
            $rp = $mem->bv_right - $mem->bv_left;
            $cut_points = $mem->bv_left;
            $cut_point = ($cut_points * $binary_per) / 100;
            $total_today = $today_income + $cut_point;
            if ($today_income < $pack_cap) {
                if ($total_today < $pack_cap) {
                    updateCommissionWithLimit($mem->user_id, $cut_point, $wallet_id, 2, 'Business Volume Matching', $network_limit, $plan_trx);
                    $mem->bv_right = $rp;
                    $mem->bv_left = 0;
                    $mem->save();
                } else {
                    $adjust_point = $pack_cap - $today_income;
                    $flush_point = $cut_point - $adjust_point;
                    updateCommissionWithLimit($mem->user_id, $adjust_point, $wallet_id, 2, 'Business Volume Matching', $network_limit, $plan_trx);

                    //update flused off
                    $details = 'Flushed-Off ' . getCommissionName(2) . ' Due to Capping';
                    updateWallet($mem->user_id, getTrx(), 7, 2, '+', getAmount($flush_point), $details, 0, str_replace(' ', '_', getCommissionName(2)), NULL);

                    $mem->bv_right = $rp;
                    $mem->bv_left = 0;
                    $mem->save();
                }
            } else {
                //update flused off
                $details = 'Flushed-Off ' . getCommissionName(2) . ' Due to Capping';
                updateWallet($mem->user_id, getTrx(), 7, 2, '+', getAmount($cut_point), $details, 0, str_replace(' ', '_', getCommissionName(2)), NULL);

                $mem->bv_right = $rp;
                $mem->bv_left = 0;
                $mem->save();
            }
            updateRankPoints($mem->user_id, $cut_points);
            return 1;
        } elseif ($mem->bv_left == $mem->bv_right & $mem->bv_right != 0 & $mem->bv_left != 0) {
            $p = $mem->bv_right - $mem->bv_left;
            $cut_points = $mem->bv_left;
            $cut_point = ($cut_points * $binary_per) / 100;
            $total_today = $today_income + $cut_point;
            if ($today_income < $pack_cap) {
                if ($total_today < $pack_cap) {
                    updateCommissionWithLimit($mem->user_id, $cut_point, $wallet_id, 2, 'Business Volume Matching', $network_limit, $plan_trx);
                    $mem->bv_right = 0;
                    $mem->bv_left = 0;
                    $mem->save();
                } else {
                    $adjust_point = $pack_cap - $today_income;
                    $flush_point = $cut_point - $adjust_point;
                    updateCommissionWithLimit($mem->user_id, $adjust_point, $wallet_id, 2, 'Business Volume Matching', $network_limit, $plan_trx);

                    //update flused off
                    $details = 'Flushed-Off ' . getCommissionName(2) . ' Due to Capping';
                    updateWallet($mem->user_id, getTrx(), 7, 2, '+', getAmount($flush_point), $details, 0, str_replace(' ', '_', getCommissionName(2)), NULL);

                    $mem->bv_right = 0;
                    $mem->bv_left = 0;
                    $mem->save();
                }
            } else {
                //update flused off
                $details = 'Flushed-Off ' . getCommissionName(2) . ' Due to Capping';
                updateWallet($mem->user_id, getTrx(), 7, 2, '+', getAmount($cut_point), $details, 0, str_replace(' ', '_', getCommissionName(2)), NULL);

                $mem->bv_right = 0;
                $mem->bv_left = 0;
                $mem->save();
            }
            updateRankPoints($mem->user_id, $cut_points);
            return 1;
        }

    }
    return 0;
}

function updateNoBV($id, $bv, $details)
{
    $user = User::find($id);
    $commission = Commission::where('status', 1)->where('id', 2)->firstOrFail();
    $count = 0;
    while ($id != "" || $id != "0") {
        if (isUserExists($id)) {
            $posid = getPositionId($id);
            if ($posid == "0") {
                break;
            }
            $posUser = User::find($posid);
            if ($posUser->plan_purchased != 0) {
                $position = getPositionLocation($id);
                $extra = UserExtra::where('user_id', $posid)->first();
                $bvlog = new BvLog();
                $bvlog->user_id = $posid;

                binaryActivation($posid, $user->id, $position);
                treeAdjust($posid, $user->id, $position);

                if ($posUser->plan_purchased != 0) {

                    $user_plan = getUserHigherPlan($posid);

                    if ($user_plan) {

                        if ($commission->is_package == 1) {
                            $binary = CommissionDetail::where('commission_id', $commission->id)->where('plan_id', $user_plan->plan_id)->first();
                        } else {
                            $binary = $commission->commissionDetail[0];
                        }

                        $res = cutPoints($posid, $binary->capping, $user_plan->plan->price, $binary->percent, $binary->commission->wallet_id, $binary->commission_limit, $user_plan->trx);

                        $count += $res;

                        if ($count == 5) {
                            //break;
                        }
                    }
                }
            }
            $id = $posid;
        } else {
            break;
        }
    }

}

function updateBV($id, $bv, $details)
{
    $user = User::find($id);
    $commission = Commission::where('status', 1)->where('id', 2)->firstOrFail();
    $count = 0;
    while ($id != "" || $id != "0") {
        if (isUserExists($id)) {
            $posid = getPositionId($id);
            if ($posid == "0") {
                break;
            }
            $posUser = User::find($posid);

            if ($posUser->status != 0) {
                $position = getPositionLocation($id);
                $extra = UserExtra::where('user_id', $posid)->first();
                $bvlog = new BvLog();
                $bvlog->user_id = $posid;

                if ($position == 1) {
                    $extra->bv_left += $bv;
                    $extra->total_bv_left += $bv;
                    $bvlog->position = '1';
                    $details = 'Received Business Volume From ' . $user->username;
                    if ($posid <= $user->ref_id) {
                        $extra->spill_bv_left += $bv;
                    }
                } else {
                    $extra->bv_right += $bv;
                    $extra->total_bv_right += $bv;
                    $bvlog->position = '2';
                    $details = 'Received Business Volume From ' . $user->username;
                    if ($posid <= $user->ref_id) {
                        $extra->spill_bv_right += $bv;
                    }
                }
                $extra->save();
                $bvlog->amount = $bv;
                $bvlog->trx_type = '+';
                $bvlog->details = $details;
                $bvlog->save();

                binaryActivation($posid, $user->id, $position);
                treeAdjust($posid, $user->id, $position);

                if ($posUser->plan_purchased != 0) {

                    $user_plan = getUserHigherPlan($posid);

                    if ($user_plan) {

                        if ($commission->is_package == 1) {
                            $binary = CommissionDetail::where('commission_id', $commission->id)->where('plan_id', $user_plan->plan_id)->first();
                        } else {
                            $binary = $commission->commissionDetail[0];
                        }

                        UnprocessedData::create([
                            'uuid' => \Illuminate\Support\Str::uuid(),
                            'method' => 'cutPoints',
                            'data' => json_encode([$posid, $binary->capping, $user_plan->plan->price, $binary->percent, $binary->commission->wallet_id, $binary->commission_limit, $user_plan->trx]),
                        ]);

                        // info('meadasd', [$posid, $binary->capping,  $user_plan->plan->price, $binary->percent, $binary->commission->wallet_id, $binary->commission_limit, $user_plan->trx]);
                        // $res = cutPoints($posid, $binary->capping,  $user_plan->plan->price, $binary->percent, $binary->commission->wallet_id, $binary->commission_limit, $user_plan->trx);

                        // $count += $res;

                        // if($count == 5){
                        //     //break;
                        // }
                    }
                }
            }

            $id = $posid;
        } else {
            break;
        }
    }

}

function binaryActivation($id = '', $user_id = '', $position = '')
{
    $user_type = getUserLastPlan($user_id)->type;
    if ($user_type != "sponsor") {
        $mem = UserExtra::where('user_id', $id)->first();
        $user = User::find($user_id);
        if ($mem->binary_active == 0 && $user->ref_id == $id) {
            if ($position == 1) {
                $mem->lb = 1;
                $mem->save();
            } else {
                $mem->rb = 1;
                $mem->save();
            }
            if ($mem->lb == 1 & $mem->rb == 1) {
                $mem->binary_active = 1;
                $mem->save();
            }
        }
    }
}

function treeAdjust($id = '', $user_id = '', $position = '')
{
    $mem = UserExtra::where('user_id', $id)->first();
    $user = User::find($user_id);
    $user_tree = UserNetwork::where('user_id', $mem->user_id)->where('mem_id', $user->id)->count();
    if ($user_tree > 0) {
        UserNetwork::where('user_id', $mem->user_id)->where('mem_id', $user->id)
            ->update([
                'is_roi' => 0,
                'is_point' => 0,
            ]);
    } else {
        UserNetwork::Create([
            'user_id' => $mem->user_id,
            'mem_id' => $user->id,
            'position' => $user->position,
            'team' => $position,
            'is_roi' => 0,
            'is_point' => 0,
        ]);
    }
}

function familyTreeAdjust($user_id = '')
{
    $user = User::find($user_id);
    $ref_id = $user->ref_id;
    $level = 1;
    while ($ref_id > 0) {
        $direct = User::find($ref_id);
        $user_family = UserFamily::where('user_id', $direct->id)->where('mem_id', $user->id)->count();
        if ($user_family == 0) {
            UserFamily::Create([
                'user_id' => $direct->id,
                'mem_id' => $user->id,
                'level' => $level,
                'weekly_roi' => 0,
                'current_roi' => 0,
            ]);
        }
        $level++;
        $ref_id = $direct->ref_id;
    }
}

function referralCommission($user_id, $wallet_id, $percent, $commission_id, $name, $limit, $plan_id = '')
{

    $user = User::find($user_id);
    $plan = Plan::find($plan_id);
    $refer = User::find($user->ref_id);

    if ($refer && $refer->status) {
        $ref_plan = getUserHigherPlan($refer->id);
        $amount = ($percent / 100) * $plan->bv;
        if ($ref_plan) {
            updateCommissionWithLimit($refer->id, $amount, $wallet_id, $commission_id, $user->username, $limit, $ref_plan->trx);
        } else {
            $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $user->username;
            updateWallet($refer->id, getTrx(), $wallet_id, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), NULL);
        }
    }
}

function cashbackCommission(User $user)
{
    $general = GeneralSetting::first();

    $user_plan = getUserHigherPlan($user->id);
    $user_plan_release = getUserLowerPlan($user->id);

    if ($user_plan) {

        $commission = Commission::where('status', 1)->where('id', 5)->firstOrFail();

        if ($commission->is_package == 1) {
            $com = CommissionDetail::where('commission_id', $commission->id)->where('plan_id', $user_plan->plan_id)->first();
        } else {
            $com = $commission->commissionDetail[0];
        }

        $amount = ($com->percent / 100) * $user_plan_release->plan->price;
        updateCommissionWithLimit($user->id, $amount, $com->commission->wallet_id, $commission->id, $general->sitename, $com->commission_limit, $user_plan->trx);

        $user->check_fairy = Carbon::now();
        $user->save();
    }
}

function vipCommission(User $user, int $with_draw_id)
{
    // if($id == 1){
    // 	echo $id;
    //     $users = Withdrawal::where(['wallet_id' => 3, 'status' => 1])->with('user')->get()->pluck('user')->unique('id');
    // 	// $users = User::whereRaw("plan_purchased != 0 and status = 1")->get();
    // }
    // elseif($id == 2){
    // 	echo $id;
    // 	$users = User::where(['plan_purchased' => 1, 'status' => 1])->where('id', '>', 4000)->get();
    // }
    // else{
    // 	echo $id;
    // 	$users = User::where(['plan_purchased' => 1, 'status' => 1])->where('id', '>', 4000)->get();
    // }

    // $total_level = CommissionDetail::where('commission_id', 4)->count();

    // foreach ($users as $user){
    //     $user_plan = getUserHigherPlan($user->id);

    //     if($user_plan){
    //         $user_levels = UserFamily::where('user_id', $user->id)->orderBy('level', 'desc')->value('level');

    //         if($user_levels >= $total_level){
    //             $user_levels = $total_level;
    //         }

    //         $level = 1;

    //         while($level <= $user_levels){
    //             $level_earning = UserFamily::where('user_id', $user->id)->where('level', $level)->sum('weekly_roi');
    //             $commission = CommissionDetail::where('commission_id', 4)->where('level', $level)->first();

    //             $amount = ($commission->percent / 100) * $level_earning;

    //             updateCommissionWithLimit($user->id, $amount, $commission->commission->wallet_id, $commission->commission->id, 'Level ' . $level, $commission->commission_limit, $user_plan->trx);

    //             $level++;
    //         }
    //     }
    // }

    $total_level = CommissionDetail::where('commission_id', 4)->count();

    $user_plan = getUserHigherPlan($user->id);

    if ($user_plan) {

        $user_levels = UserFamily::where('user_id', $user->id)->orderBy('level', 'desc')->value('level');

        if ($user_levels >= $total_level) {
            $user_levels = $total_level;
        }

        $level = 1;

        while ($level <= $user_levels) {
            $level_earning = UserFamily::where('user_id', $user->id)->where('level', $level)->sum('weekly_roi');
            $commission = CommissionDetail::where('commission_id', 4)->where('level', $level)->first();

            $amount = ($commission->percent / 100) * $level_earning;

            updateUniLevelBonus($user, $amount, $commission->commission->wallet_id, $commission->commission->id, 'Level ' . $level, $commission->commission_limit, $user_plan->trx, $with_draw_id);

            $level++;
        }

    }
}

function updateUniLevelBonus(User $user, float $amount, int $wallet_id, int $commission_id, string $msg, float $commission_limit, string $tnx, int $with_draw_id)
{
    $plan = PurchasedPlan::where('trx', $tnx)->firstOrFail();

    if ($amount != 0) {
        if ($commission_limit != 0) {

            $limit = checkLimit($amount, $commission_limit, $plan);

            if ($limit <= 100) {
                $plan->limit_consumed = $limit;
                $plan->save();

                //update commission
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $msg;
                addTransactionWithOutUpdateWallet($user, getTrx(), $wallet_id, $commission_id, getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $tnx, $with_draw_id);
            } else {
                //set limit
                $remaining_amount = setLimit($amount, $commission_limit, $plan);

                //update commission
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $msg;
                addTransactionWithOutUpdateWallet($user, getTrx(), $wallet_id, $commission_id, getAmount($amount - $remaining_amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $tnx, $with_draw_id);


                while ($remaining_amount != 0) {
                    $remaining_amount = limitRemaining($user->id, $remaining_amount, $wallet_id, $commission_id, $msg, $commission_limit, $plan);
                }

            }

        } else {
            //update commission
            $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $msg;
            addTransactionWithOutUpdateWallet($user, getTrx(), $wallet_id, $commission_id, getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $tnx, $with_draw_id);
        }
    }

    return 0;
}

function addTransactionWithOutUpdateWallet(User $user, string $tnx, int $wallet_id, int $commission_id, float $amount, string $details, float $charges, string $remarks, string $plan_trx, int $with_draw_id)
{
    $transaction = new Transaction();
    $transaction->withdraw_id = $with_draw_id;
    $transaction->user_id = $user->id;
    $transaction->amount = $amount;
    $transaction->charge = $charges;
    $transaction->details = $details;
    $transaction->trx = $tnx;
    $transaction->plan_trx = $plan_trx;
    $transaction->country = User::where('id', $user->id)->first()->address->country;
    $transaction->remark = $remarks;
    $transaction->commission_id = $commission_id;

    $wallet = UserWallet::where(['user_id' => $user->id, 'wallet_id' => $wallet_id])->first();

    $transaction->post_balance = getAmount($wallet->balance);

    if ($wallet->wallet->passive > 0) {

        $commission_ammount = ($amount * $wallet->wallet->passive) / 100;
        $amount = $amount - $commission_ammount;
        $passive_wallet = UserWallet::where(['user_id' => $user->id, 'wallet_id' => 9])->first();
        $passive_wallet->balance += $commission_ammount;
        // $passive_wallet->save();

        $details2 = 'Received Income in ' . getWalletName(9) . ' From ' . getCommissionName($commission_id);

        $transaction2 = new Transaction();
        $transaction2->withdraw_id = $with_draw_id;
        $transaction2->user_id = $user->id;
        $transaction2->amount = $commission_ammount;
        $transaction2->charge = $charges;
        $transaction2->details = $details2;
        $transaction2->trx = $tnx;
        $transaction2->plan_trx = $plan_trx;
        $transaction2->country = User::where('id', $user->id)->first()->address->country;
        $transaction2->remark = 'passive_income';
        $transaction2->commission_id = $commission_id;
        $transaction2->post_balance = getAmount($passive_wallet->balance);
        $transaction2->trx_type = '+';
        $transaction2->wallet_id = 9;
        $transaction2->save();

        $transaction->amount = $amount;
    }

    // $wallet->balance += $amount;
    $transaction->trx_type = '+';
    $transaction->wallet_id = $wallet_id;

    // $wallet->save();
    $transaction->save();

    return ['success', $details];
}

function vipCommissionSet(int $user_id)
{
    // $user_familys = UserFamily::all();
    // foreach($user_familys as $user_family){
    //     $user_family->current_roi = $user_family->weekly_roi;
    //     $user_family->weekly_roi = 0;
    //     $user_family->save();
    // }

    $user_families = UserFamily::where('user_id', $user_id)->get();

    foreach ($user_families as $user_family) {
        $user_family->current_roi = $user_family->weekly_roi;
        $user_family->weekly_roi = 0;
        $user_family->save();
    }
}

function checkUserPlanCount($user_id)
{
    return PurchasedPlan::where('user_id', $user_id)->where('is_expired', 0)->orderBy('id', 'desc')->count();
}

function getUserLastPlan($user_id)
{
    return PurchasedPlan::where('user_id', $user_id)->where('is_expired', 0)->orderBy('id', 'desc')->first();
}

function getUserHigherPlan($user_id)
{
    return PurchasedPlan::where('user_id', $user_id)->where('is_expired', 0)->orderBy('plan_id', 'desc')->first();
}

function getPlanWithAmount($user_id, $amount)
{
    $plan = Plan::where('price', $amount)->where('id', '!=', 0)->first();
    return PurchasedPlan::where('user_id', $user_id)->where('plan_id', $plan->id)->orderBy('id', 'desc')->first();
}

function getUserLowerPlan($user_id)
{
    return PurchasedPlan::where('user_id', $user_id)->where('is_expired', 0)->orderBy('plan_id', 'asc')->first();
}

/*
===============TREEE===============
*/

function getPositionUser($id, $position)
{
    return User::where('pos_id', $id)->where('position', $position)->first();
}

function showTreePage($id)
{
    $res = array_fill_keys(array('b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o'), null);
    $res['a'] = User::find($id);

    $res['b'] = getPositionUser($id, 1);
    if ($res['b']) {
        $res['d'] = getPositionUser($res['b']->id, 1);
        $res['e'] = getPositionUser($res['b']->id, 2);
    }
    if ($res['d']) {
        $res['h'] = getPositionUser($res['d']->id, 1);
        $res['i'] = getPositionUser($res['d']->id, 2);
    }
    if ($res['e']) {
        $res['j'] = getPositionUser($res['e']->id, 1);
        $res['k'] = getPositionUser($res['e']->id, 2);
    }
    $res['c'] = getPositionUser($id, 2);
    if ($res['c']) {
        $res['f'] = getPositionUser($res['c']->id, 1);
        $res['g'] = getPositionUser($res['c']->id, 2);
    }
    if ($res['f']) {
        $res['l'] = getPositionUser($res['f']->id, 1);
        $res['m'] = getPositionUser($res['f']->id, 2);
    }
    if ($res['g']) {
        $res['n'] = getPositionUser($res['g']->id, 1);
        $res['o'] = getPositionUser($res['g']->id, 2);
    }
    return $res;
}

function showSingleUserinTreeUser($user)
{
    $res = '';
    if ($user) {
        if ($user->plan_purchased == 0) {
            $userType = "free-user";
            $stShow = "Free";
            $planName = '';
            $img = getImage('assets/images/user/profile/free_entry.png', '120x120');
        } else {
            $userType = "paid-user";
            $stShow = "Paid";
            $planName = $user->plan[0]->plan->name;
            $img = getImage('assets/images/user/profile/default.png', '120x120');
        }

        if ($user->storm_plan == 1) {
            $stormPlan = "";
        } else {
            $stormPlan = "";
        }

        $refby = getUserById($user->ref_id)->username ?? '';
        if (auth()->guard('admin')->user()) {
            $hisTree = route('admin.users.other.tree', $user->username);
        } else {
            $hisTree = route('user.other.tree', $user->username);
        }

        $extraData = " data-name=\"$user->username\"";
        $extraData .= " data-treeurl=\"$hisTree\"";
        $extraData .= " data-status=\"$stShow\"";
        $extraData .= " data-plan=\"$planName\"";
        $extraData .= " data-stormplan=\"$stormPlan\"";
        $extraData .= " data-image=\"$img\"";
        $extraData .= " data-refby=\"$refby\"";
        $extraData .= " data-lpaid=\"" . @$user->userExtra->paid_left . "\"";
        $extraData .= " data-rpaid=\"" . @$user->userExtra->paid_right . "\"";
        $extraData .= " data-lfree=\"" . @$user->userExtra->free_left . "\"";
        $extraData .= " data-rfree=\"" . @$user->userExtra->free_right . "\"";
        $extraData .= " data-lbv=\"" . getAmount(@$user->userExtra->bv_left) . "\"";
        $extraData .= " data-rbv=\"" . getAmount(@$user->userExtra->bv_right) . "\"";

        $res .= "<div class=\"person showDetails\" type=\"button\" $extraData>";
        $res .= "<img src=\"$img\" alt=\"*\"  class=\"$userType\">";
        $res .= "<p class=\"name\">$user->username</p>";
        $res .= " </div>";

    } else {
        $img = getImage('assets/images/user/profile/no_user.png', '120x120');

        $res .= "<div class=\"person\" type=\"button\" >";
        $res .= "<img src=\"$img\" alt=\"*\"  class=\"no-user\">";
        $res .= "<p class=\"name\">Free Space</p>";
        $res .= " </div>";
    }

    return $res;

}


function showSingleUserinTree($user)
{
    $res = '';
    if ($user) {
        if ($user->plan_purchased == 0) {
            $userType = "free-user";
            $stShow = "Free";
            $planName = '';
        } else {
            $userType = "paid-user";
            $stShow = "Paid";
            $planName = $user->plan[0]->plan->name;
        }

        $img = getImage('assets/images/user/profile/' . $user->image, '120x120');
        $refby = getUserById($user->ref_id)->fullname ?? '';
        if (auth()->guard('admin')->user()) {
            $hisTree = route('admin.users.other.tree', $user->username);
        } else {
            $hisTree = route('user.other.tree', $user->username);
        }

        $extraData = " data-name=\"$user->fullname\"";
        $extraData .= " data-treeurl=\"$hisTree\"";
        $extraData .= " data-status=\"$stShow\"";
        $extraData .= " data-plan=\"$planName\"";
        $extraData .= " data-image=\"$img\"";
        $extraData .= " data-refby=\"$refby\"";
        $extraData .= " data-lpaid=\"" . @$user->userExtra->paid_left . "\"";
        $extraData .= " data-rpaid=\"" . @$user->userExtra->paid_right . "\"";
        $extraData .= " data-lfree=\"" . @$user->userExtra->free_left . "\"";
        $extraData .= " data-rfree=\"" . @$user->userExtra->free_right . "\"";
        $extraData .= " data-lbv=\"" . getAmount(@$user->userExtra->bv_left) . "\"";
        $extraData .= " data-rbv=\"" . getAmount(@$user->userExtra->bv_right) . "\"";

        $res .= "<div class=\"user showDetails\" type=\"button\" $extraData>";
        $res .= "<img src=\"$img\" alt=\"*\"  class=\"$userType\">";
        $res .= "<p class=\"user-name\">$user->username</p>";

    } else {
        $img = getImage('assets/images/user/profile/', '120x120');

        $res .= "<div class=\"user\" type=\"button\">";
        $res .= "<img src=\"$img\" alt=\"*\"  class=\"no-user\">";
        $res .= "<p class=\"user-name\">Free Space</p>";
    }

    $res .= " </div>";
    $res .= " <span class=\"line\"></span>";

    return $res;

}

/*
===============TREE AUTH==============
*/
function treeAuth($whichID, $whoID)
{

    if ($whichID == $whoID) {
        return true;
    }
    $formid = $whichID;
    while ($whichID != "" || $whichID != "0") {
        if (isUserExists($whichID)) {
            $posid = getPositionId($whichID);
            if ($posid == "0") {
                break;
            }
            $position = getPositionLocation($whichID);
            if ($posid == $whoID) {
                return true;
            }
            $whichID = $posid;
        } else {
            break;
        }
    }
    return 0;
}

function displayRating($val)
{
    $result = '';
    for ($i = 0; $i < intval($val); $i++) {
        $result .= '<i class="la la-star text--warning"></i>';
    }
    if (fmod($val, 1) == 0.5) {
        $i++;
        $result .= '<i class="las la-star-half-alt text--warning"></i>';
    }
    for ($k = 0; $k < 5 - $i; $k++) {
        $result .= '<i class="lar la-star text--warning"></i>';
    }
    return $result;
}

function getReferenceId($id = '')
{
    $ref = User::where('id', $id)->first()->ref_id;
    return $ref;
}

function createWallets($id = '')
{
    $wallets = Wallet::all();
    foreach ($wallets as $wallet) {
        $user_wallet['user_id'] = $id;
        $user_wallet['wallet_id'] = $wallet->id;
        $user_wallet['balance'] = 0;
        UserWallet::create($user_wallet);
    }
}

function updateWallet($user_id = '', $trx = '', $wallet_id = '', $commission_id = '', $opration = '', $amount = '', $details = '', $charges = '', $remarks = '', $plan_trx = '')
{

    $user = User::findOrFail($user_id);

    $transaction = new Transaction();
    $transaction->user_id = $user_id;
    $transaction->amount = $amount;
    $transaction->charge = $charges;
    $transaction->details = $details;
    $transaction->trx = $trx;
    $transaction->plan_trx = $plan_trx;
    $transaction->country = User::where('id', $user_id)->first()->address->country;
    $transaction->remark = $remarks;
    $transaction->commission_id = $commission_id;

    $wallet = UserWallet::where(['user_id' => $user_id, 'wallet_id' => $wallet_id])->first();

    $transaction->post_balance = getAmount($wallet->balance);


    if ($opration == '+') {
        if ($wallet->wallet->passive > 0) {
            $commission_ammount = ($amount * $wallet->wallet->passive) / 100;
            $amount = $amount - $commission_ammount;
            $passive_wallet = UserWallet::where(['user_id' => $user_id, 'wallet_id' => 9])->first();
            $passive_wallet->balance += $commission_ammount;
            $passive_wallet->save();

            $details2 = 'Received Income in ' . getWalletName(9) . ' From ' . getCommissionName($commission_id);

            $transaction2 = new Transaction();
            $transaction2->user_id = $user_id;
            $transaction2->amount = $commission_ammount;
            $transaction2->charge = $charges;
            $transaction2->details = $details2;
            $transaction2->trx = $trx;
            $transaction2->plan_trx = $plan_trx;
            $transaction2->country = User::where('id', $user_id)->first()->address->country;
            $transaction2->remark = 'passive_income';
            $transaction2->commission_id = $commission_id;
            $transaction2->post_balance = getAmount($passive_wallet->balance);
            $transaction2->trx_type = '+';
            $transaction2->wallet_id = 9;
            $transaction2->save();

            $transaction->amount = $amount;
        }

        $wallet->balance += $amount;
        $transaction->trx_type = '+';
        $transaction->wallet_id = $wallet_id;

        $wallet->save();
        $transaction->save();

        return ['success', $details];
    } elseif ($opration == '-') {
        if ($amount > $wallet->balance) {
            return ['error', $user->username . ' has insufficient balance.'];
        }
        $wallet->balance -= $amount;
        $transaction->trx_type = '-';
        $transaction->wallet_id = $wallet_id;

        $wallet->save();
        $transaction->save();

        return ['success', $details];
    }

}

function checkLimit($amount = '', $network_limits = '', $plan)
{
    $network_limit = $plan->plan->price * $network_limits;
    $amount_limit = ($amount / $network_limit) * 100;
    $limit_consumed = $plan->limit_consumed;
    return $amount_limit + $limit_consumed;
}

function setLimit($amount = '', $network_limit = '', $plan)
{
    $network_limit = $plan->plan->price * $network_limit;
    $remaining_limit = 100 - $plan->limit_consumed;
    $adjusted_amount = ($remaining_limit * $network_limit) / 100;
    $total_limit = 100;

    $plan->limit_consumed = $total_limit;
    $plan->is_expired = 1;
    $plan->save();

    return $amount - $adjusted_amount;
}

function limitRemaining($id = '', $amount = '', $wallet_id = '', $commission_id = '', $from = '', $network_limit = '', $plan)
{

    $commission = Commission::where('status', 1)->where('id', $commission_id)->firstOrFail();
    $new_trx = getPlanExceptTrx($id, $plan->trx);

    if ($new_trx != $plan->trx) {
        //if have anyother active package send money with it
        $new_plan = PurchasedPlan::where('trx', $new_trx)->firstOrFail();

        if ($commission->is_package == 1) {
            $network_limit = CommissionDetail::where('commission_id', $commission->id)->where('plan_id', $new_plan->plan_id)->firstOrFail()->commission_limit;
        }

        $limit = checkLimit($amount, $network_limit, $new_plan);
        if ($limit <= 100) {
            $new_plan->limit_consumed = $limit;
            $new_plan->save();

            //update commission
            if ($commission->id == 1) {
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $new_plan->plan->name . ' Plan';
            } else {
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
            }

            updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $new_trx);

            return 0;
        } else {
            //set limit
            $remaining_amount = setLimit($amount, $network_limit, $new_plan);

            //update commission
            if ($commission->id == 1) {
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $new_plan->plan->name . ' Plan';
            } else {
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
            }
            updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($remaining_amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $new_trx);

            return $remaining_amount;

        }

    } else {
        if ($amount >= $plan->plan->price) {
            if ($plan->auto_renew == 1) {
                // renew current package with the amount
                $user = User::find($id);
                $user->plan_purchased = 1;
                $user->save();

                $plan_trx = getTrx();
                $details = $user->username . ' Renew ' . $plan->plan->name . ' plan';

                updateWallet($id, $plan_trx, $wallet_id, $commission_id, '-', getAmount($plan->plan->price), $details, 0, 'auto_renew_plan', NULL);

                $plan->limit_consumed = 0;
                $plan->is_expired = 0;
                $plan->trx = $plan_trx;
                $plan->save();

                CronUpdate::create([
                    'user_id' => $user->id,
                    'type' => 'purchased_plan',
                    'amount' => $plan->plan->price,
                    'details' => $details,
                    'status' => 0,
                ]);

                $limit = checkLimit($amount, $network_limit, $plan);
                if ($limit <= 100) {
                    $plan->limit_consumed = $limit;
                    $plan->save();

                    //update commission
                    if ($commission->id == 1) {
                        $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $plan->plan->name . ' Plan';
                    } else {
                        $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
                    }

                    updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $plan->trx);

                    return 0;
                } else {
                    //set limit
                    $remaining_amount = setLimit($amount, $network_limit, $plan);

                    //update commission
                    if ($commission->id == 1) {
                        $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $plan->plan->name . ' Plan';
                    } else {
                        $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
                    }
                    updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($remaining_amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $plan->trx);

                    return $remaining_amount;

                }

            } else {
                //if not auto renew flush the amount
                //update flused off
                $details = 'Flushed-Off ' . getCommissionName($commission_id) . ' Due to Network Limit';
                updateWallet($id, getTrx(), 6, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $plan->trx);

                return 0;
            }
        } else {
            //if not renew flush the amount
            //update flused off
            $details = 'Flushed-Off ' . getCommissionName($commission_id) . ' Due to Network Limit';
            updateWallet($id, getTrx(), 6, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $plan->trx);

            return 0;
        }
    }
}

function updateCommissionWithLimit($id = '', $amount = '', $wallet_id = '', $commission_id = '', $from = '', $network_limit = '', $trx = '')
{
    $plan = PurchasedPlan::where('trx', $trx)->firstOrFail();
    $nl = $network_limit;
    if ($amount != 0) {
        if ($network_limit != 0) {
            $limit = checkLimit($amount, $network_limit, $plan);
            if ($limit <= 100) {
                $plan->limit_consumed = $limit;
                $plan->save();

                //update commission
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
                updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $trx);
            } else {
                //set limit
                $remaining_amount = setLimit($amount, $network_limit, $plan);

                //update commission
                $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
                updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($amount - $remaining_amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $trx);

                while ($remaining_amount != 0) {
                    $remaining_amount = limitRemaining($id, $remaining_amount, $wallet_id, $commission_id, $from, $network_limit, $plan);
                }

            }
            //adjustLimit($id, $nl);
        } else {
            //update commission
            $details = 'Received ' . getCommissionName($commission_id) . ' From ' . $from;
            updateWallet($id, getTrx(), $wallet_id, $commission_id, '+', getAmount($amount), $details, 0, str_replace(' ', '_', getCommissionName($commission_id)), $trx);
        }
    }

    return 0;
}

function adjustLimit($user_id = '', $network_limit = '')
{
    $user_plans = PurchasedPlan::where(['user_id' => $user_id, 'is_expired' => 0])->get();
    foreach ($user_plans as $plan) {
        echo $network_earning = Transaction::where(['plan_trx' => $plan->trx, 'trx_type' => '+'])->sum('amount');
        $plan_limit = $plan->plan->price * $network_limit;
        $limit = ($network_earning / $plan_limit) * 100;
        $plan->limit_consumed = $limit;
        //$plan->save();
    }
}

function getCommissionName($id)
{
    return Commission::where('status', 1)->where('id', $id)->firstOrFail()->name;
}

function getCommission($id)
{
    return Commission::where('status', 1)->where('id', $id)->firstOrFail();
}

function getWalletName($id)
{
    return Wallet::where('status', 1)->where('id', $id)->firstOrFail()->name;
}

function getUserPlanPurchasedStatus($user_id = '')
{
    return PurchasedPlan::where(['user_id' => $user_id, 'is_expired' => 0])->count();
}

function rewardRelease()
{
    $rank_achievers = RankAchiever::where('is_sent', 0)->get();
    $general = GeneralSetting::first();
    foreach ($rank_achievers as $rank_achiever) {
        $user = User::find($rank_achiever->user_id);
        $user_plan = getUserHigherPlan($user->id);
        $amount = $rank_achiever->reward;
        $send_date = Carbon::parse($rank_achiever->send_date);
        $now = Carbon::now();
        if ($send_date->lte($now)) {
            if ($user_plan) {
                $commission = Commission::where('status', 1)->where('id', 8)->firstOrFail();
                if ($commission->is_package == 1) {
                    $com = CommissionDetail::where('commission_id', $commission->id)->where('plan_id', $user_plan->plan_id)->first();
                } else {
                    $com = $commission->commissionDetail[0];
                }

                updateCommissionWithLimit($user->id, $amount, $com->commission->wallet_id, $commission->id, $general->sitename, $com->commission_limit, $user_plan->trx);
            }

            $rank_achiever->is_sent = 1;
            $rank_achiever->save();
        }
    }
}

function updateRankPoints($id, $points)
{
    $user = User::find($id);
    $user->total_bv += $points;
    $user->save();
    $ranks = Rank::all();
    foreach ($ranks as $rank) {
        $user = User::where('id', $id)->first();
        if (getRankPoints($id) >= $rank->points) {
            //$directs = User::whereRaw("ref_id = ? and plan_purchased != 0 and rank_id >= ?", [$id, $rank->required])->count();
            if ($user->rank_id != $rank->id) {
                $user->rank_id = $rank->id;
                $user->save();
                $rank_achievers_count = RankAchiever::where('user_id', $user->id)->where('rank_id', $rank->id)->count();
                if ($rank_achievers_count == 0) {
                    $date = Carbon::now();
                    $date->addDays(30);
                    if ($rank->value != 0) {
                        RankAchiever::Create([
                            'user_id' => $user->id,
                            'rank_id' => $rank->id,
                            'reward' => $rank->value,
                            'is_sent' => 0,
                            'send_date' => $date
                        ]);
                    }

                    //$message = 'Congratulations on the achievement of ' . $rank->name . ' Rank.';
                    //send_email($user->email, 'Congratulations ' . $rank->name . ' Rank Achiever', $user->first_name, $message);
                }
            }
        }
    }
}

function updateRankStatus($id)
{
    $user = User::find($id);
    $ranks = Rank::all();
    foreach ($ranks as $rank) {
        if (getRankPoints($id) >= $rank->points) {
            if ($user->rank_id != $rank->id) {
                $user->rank_id = $rank->id;
                $user->save();
                $rank_achievers_count = RankAchiever::where('user_id', $user->id)->where('rank_id', $rank->id)->count();
                if ($rank_achievers_count == 0) {
                    $date = Carbon::now();
                    $date->addDays(30);
                    if ($rank->value != 0) {
                        RankAchiever::Create([
                            'user_id' => $user->id,
                            'rank_id' => $rank->id,
                            'reward' => $rank->value,
                            'is_sent' => 0,
                            'send_date' => $date
                        ]);
                    }

                    //$message = 'Congratulations on the achievement of ' . $rank->name . ' Rank.';
                    //send_email($user->email, 'Congratulations ' . $rank->name . ' Rank Achiever', $user->first_name, $message);
                }
            }
        }
    }
}

function getPlanExceptTrx($id, $trx)
{
    $plans = PurchasedPlan::where('user_id', $id)->where('trx', '!=', $trx)->where('is_expired', 0)->get();
    foreach ($plans as $plan) {
        return $plan->trx;
    }

    return $trx;
}

function updateTransaction($user_id = '', $trx = '', $wallet_id = '', $commission_id = '', $opration = '', $amount = '', $details = '', $charges = '', $remarks = '', $plan_trx = '')
{

    $transaction = new Transaction();
    $transaction->user_id = $user_id;
    $transaction->amount = $amount;
    $transaction->charge = $charges;
    $transaction->details = $details;
    $transaction->trx = $trx;
    $transaction->country = User::where('id', $user_id)->first()->address->country;
    $transaction->remark = $remarks;
    $transaction->commission_id = $commission_id;
    if ($plan_trx) {
        $transaction->plan_trx = $plan_trx;
    }

    $transaction->trx_type = $opration;
    $transaction->save();


    return ['success', $details];
}

function checkPaidStatusDaily()
{
    $users = User::where("status", 1)->where("plan_purchased", 0)->get();
    foreach ($users as $user) {
        $days = 30;
        $join_date = Carbon::parse($user->created_at);
        $now = Carbon::now();
        $dif = $join_date->diffInDays($now);
        if ($dif >= 30) {
            $user_wallets = UserWallet::where('user_id', $user->id)->where('wallet_id', '<', 6)->where('balance', '!=', 0)->get();
            foreach ($user_wallets as $user_wallet) {
                $details = 'Flushed-Off Amount due to none activation';
                updateWallet($user_wallet->user_id, getTrx(), $user_wallet->wallet_id, NULL, '-', getAmount($user_wallet->balance), $details, 0, "wallet_flused", NULL);
                updateWallet($user_wallet->user_id, getTrx(), 6, NULL, '+', getAmount($user_wallet->balance), $details, 0, "flushed_activation", NULL);
            }

            $user_extra = UserExtra::where('user_id', $user->id)->first();
            $user_extra->bv_left = 0;
            $user_extra->bv_right = 0;
            $user_extra->save();

            $user->total_bv = 0;
            $user->save();
        }
        if ($dif >= 90) {
            $user->status = 0;
            $user->save();
        }
    }
}

function checkBlocks($user_id)
{
    $count_block1 = 0;
    $count_block2 = 0;
    $count_block3 = 0;

    $user = User::find($user_id);
    $user_block1 = UserFamily::whereRaw('user_id = ' . $user_id . ' and level = 1 and plan_id != 0')->get();
    if (count($user_block1) >= 2) {
        $count_block1++;
        foreach ($user_block1 as $user1) {
            $user_block2 = UserFamily::whereRaw('user_id = ' . $user1->mem_id . ' and level = 1 and plan_id != 0')->get();
            if (count($user_block2) >= 2) {
                $count_block2++;
                foreach ($user_block2 as $user2) {
                    $user_block3 = UserFamily::whereRaw('user_id = ' . $user2->mem_id . ' and level = 1 and plan_id != 0')->get();
                    if (count($user_block3) >= 2) {
                        $count_block3++;
                    }
                }
            }
        }
    }

    if ($count_block1 > 0 && $user->block1 == 0) {
        $user->block1 = 1;
    }

    if ($count_block2 > 1 && $user->block2 == 0) {
        $user->block2 = 1;
    }

    if ($count_block3 > 3 && $user->block3 == 0) {
        $user->block3 = 1;
    }

    $user->save();
}

function release3Blocks()
{
    $commission = Commission::where('status', 1)->where('id', 6)->firstOrFail();
    $com = $commission->commissionDetail[0];
    $users_block1 = User::where('block1', 1)->get();

    foreach ($users_block1 as $user) {
        $user_plan = getUserHigherPlan($user->id);
        if ($user_plan) {
            $block1_package = UserFamily::whereRaw('user_id = ' . $user->id . ' and level = 1 and plan_id != 0')->orderBy('plan_id', 'asc')->firstOrFail()->plan_id;
            $plan1 = Plan::find($block1_package);
            $amount = ($com->percent / 100) * $plan1->price;
            updateCommissionWithLimit($user->id, $amount, $com->commission->wallet_id, $commission->id, 'Block 1', $com->commission_limit, $user_plan->trx);

            $user->block1 = 2;
            $user->save();
        }
    }

    $users_block2 = User::where('block2', 1)->get();

    foreach ($users_block2 as $user) {
        $user_plan = getUserHigherPlan($user->id);
        if ($user_plan) {
            $block2_package = UserFamily::whereRaw('user_id = ' . $user->id . ' and level = 2 and plan_id != 0')->orderBy('plan_id', 'asc')->firstOrFail()->plan_id;
            $plan2 = Plan::find($block2_package);
            $amount2 = ($com->percent / 100) * $plan2->price;
            updateCommissionWithLimit($user->id, $amount2, $com->commission->wallet_id, $commission->id, 'Block 2', $com->commission_limit, $user_plan->trx);

            $user->block2 = 2;
            $user->save();
        }
    }

    $users_block3 = User::where('block3', 1)->get();

    foreach ($users_block3 as $user) {
        $user_plan = getUserHigherPlan($user->id);
        if ($user_plan) {
            $block3_package = UserFamily::whereRaw('user_id = ' . $user->id . ' and level = 3 and plan_id != 0')->orderBy('plan_id', 'asc')->firstOrFail()->plan_id;
            $plan3 = Plan::find($block3_package);
            $amount3 = ($com->percent / 100) * $plan3->price;
            updateCommissionWithLimit($user->id, $amount3, $com->commission->wallet_id, $commission->id, 'Block 3', $com->commission_limit, $user_plan->trx);

            $user->block3 = 2;
            $user->save();
        }
    }
}

function checkStatusNowPayments()
{

    $deposits = Deposit::where('method_code', 507)->where('status', 0)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

    foreach ($deposits as $deposit) {

        $nowpaymentsAcc = json_decode($deposit->gateway_currency()->gateway_parameter);

        $header = array(
            'x-api-key: ' . trim($nowpaymentsAcc->api_key),
            'Content-Type: application/json'
        );

        $response = json_decode(curlGetContent("https://api.nowpayments.io/v1/payment/$deposit->try", $header));

        if (count((array)$response) > 1) {
            if (@$response->payment_status == 'finished' || @$response->payment_status == 'paid') {
                PaymentController::userDataUpdate($deposit->trx);
            }
        }

    }
}

function flushWallets()
{
    $user_wallets = UserWallet::where('wallet_id', '<', 6)->where('balance', '!=', 0)->get();
    foreach ($user_wallets as $user_wallet) {
        $details = 'Flushed-Off Amount for Balance Adjustment';
        updateWallet($user_wallet->user_id, getTrx(), $user_wallet->wallet_id, NULL, '-', getAmount($user_wallet->balance), $details, 0, "wallet_adjustment", NULL);
        updateWallet($user_wallet->user_id, getTrx(), 6, NULL, '+', getAmount($user_wallet->balance), $details, 0, "flushed_adjustment", NULL);
    }
}

function UserExtrasAdjust()
{
    $users_extras = UserExtra::all();
    foreach ($users_extras as $user_extra) {
        if ($user_extra->binary_active == 0) {
            $user_extra->total_bv_left = $user_extra->bv_left;
            $user_extra->total_bv_right = $user_extra->bv_right;
            $user_extra->spill_bv_right = $user_extra->bv_right;
            $user_extra->spill_bv_left = $user_extra->bv_left;
        } else {
            $user = User::where('id', $user_extra->user_id)->first();
            $points = $user->total_bv;
            if ($user_extra->bv_left == 0) {
                $user_extra->total_bv_left = $points;
                $user_extra->total_bv_right = $user_extra->bv_right;
                $user_extra->spill_bv_right = $user_extra->bv_right;
                $user_extra->spill_bv_left = $points;
            } elseif ($user_extra->bv_right == 0) {
                $user_extra->total_bv_left = $user_extra->bv_left;
                $user_extra->total_bv_right = $points;
                $user_extra->spill_bv_right = $points;
                $user_extra->spill_bv_left = $user_extra->bv_left;
            }
        }
        $user_extra->save();
    }
}

function getRankPoints($user_id)
{
    $user = User::find($user_id);
    return $user->total_bv;
}

function autoCompounding()
{
    $general = GeneralSetting::first();
    $release_date = getAmount($general->bal_trans_per_charge);
    $current_date = Carbon::now();
    if ($current_date->dayOfWeek == $release_date) {
        $user_plans = PurchasedPlan::where('auto_compounding', 1)->where('is_roi', 1)->get();
        foreach ($user_plans as $user_plan) {
            $roi_status = Transaction::where(['commission_id' => 1, 'user_id' => $user_plan->user_id, 'plan_trx' => $user_plan->trx])
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count();
            if ($roi_status == 0) {
                roiReturn($user_plan->user_id, 0, $user_plan->trx);
            }
        }
    }
}

function stormCommission($id = '')
{

    $total_level = CommissionDetail::where('commission_id', 9)->count();
    $family_users = UserFamily::where('mem_id', $id)->where('level', '<=', $total_level)->orderBy('level', 'desc')->get();
    foreach ($family_users as $family_user) {
        $user = User::find($id);
        $user_plan = getUserHigherPlan($family_user->user_id);
        if ($user_plan) {
            $storm_plan = Plan::where('id', 0)->first();
            $commission = CommissionDetail::where('commission_id', 9)->where('level', $family_user->level)->first();
            $amount = ($commission->percent / 100) * $storm_plan->price;
            if ($family_user->user->storm_plan) {
                updateCommissionWithLimit($family_user->user_id, $amount, $commission->commission->wallet_id, $commission->commission->id, $user->username . ' at Level ' . $family_user->level, $commission->commission_limit, $user_plan->trx);
            } else {
                updateWallet($family_user->user_id, getTrx(), 6, 9, '+', getAmount($amount), 'Flushed Storm Commission From ' . $user->username . ' at Level ' . $family_user->level, 0, "flushed_storm", NULL);
            }
        }
    }

}

// function carShare($id = '', $direct_sale = '', $date_today = '')
// {
//     $user = User::find($id);
//     $user_plan = getUserHigherPlan($user->id);
//     $general = GeneralSetting::first();
//     $commissions = CommissionDetail::where('commission_id', 7)->orderBy('id', 'desc')->get();
//     $transaction = Transaction::where('remark', 'Car_Bonus')->where('user_id', $user->id)
//         ->where(function ($q) use ($user, &$date_today) {
//             $q->where('created_at', '>=', Carbon::parse($user->check_car)->format('Y-m-d'))
//                 ->where('created_at', '<', Carbon::parse($date_today)->addDays(1)->format('Y-m-d'));
//         })
//         ->first();

//     if (isset($transaction)) {
//         $user_wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', 4)->first();
//         if ($direct_sale >= 25000 && $direct_sale < 50000) {
//             $transaction->amount = 500;
//             $user_wallet->balance = 500;
//         }
//         if ($direct_sale >= 50000 && $direct_sale < 100000) {
//             $transaction->amount = 1000;
//             $user_wallet->balance = 1000;
//         }
//         if ($direct_sale >= 100000) {
//             $transaction->amount = 2000;
//             $user_wallet->balance = 2000;
//         }
//         $transaction->update();
//         $user_wallet->update();
//     } else {
//         foreach ($commissions as $commission) {
//             if ($commission->direct <= $direct_sale) {
//                 updateCommissionWithLimit($user->id, $commission->percent, $commission->commission->wallet_id, $commission->commission_id, $general->sitename, $commission->commission_limit, $user_plan->trx);
//                 $user->check_car = NULL;
//                 $user->save();
//             } else {
//                 $user->check_car = NULL;
//                 $user->save();
//             }
//         }
//     }
// }

function checkSponsorWithdraw($id = '')
{
    $user = User::find($id);
    $general = GeneralSetting::first();
    $paid_account = PurchasedPlan::where('user_id', $id)->where('type', 'paid')->count();
    if ($paid_account > 0) {
        return 1;
    } else {
        $plan_price = PurchasedPlan::where('user_id', $id)->where('type', 'sponsor')->first()->amount;
        $direct_sales = UserFamily::whereRaw('user_id = ' . Auth::id() . ' and level = 1 ')->get();
        $total_direct_sale = 0;
        foreach ($direct_sales as $direct_sale) {
            if ($direct_sale->plan_id != 0) {
                $total_direct_sale += Plan::where('id', $direct_sale->plan_id)->firstOrFail()->price;
            }
        }

        if (($general->user1_detail * $plan_price) <= $total_direct_sale) {
            return 1;
        } else {
            return 0;
        }
    }

}
