<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-mail', function() {
    return 1;
    // $general = \App\Models\GeneralSetting::first();

    // $email_template = \App\Models\EmailTemplate::where('act', "PASS_RESET_DONE")->where('email_status', 1)->first();

    // $html = shortCodeReplacer("{{name}}", "Ryan", $general->email_template);
    // $html = shortCodeReplacer("{{message}}", $email_template->email_body, $html);
    
    // if (empty($html)) {
    //     $html = $email_template->email_body;
    // }
    $html = "hello millionaires";
    \Illuminate\Support\Facades\Mail::html($html, function ($message) {
        $message->to('sohaibfaheem44@gmail.com')
            ->from(config('mail.mailers.smtp.username'), "Millionare")
            ->subject('Test');
    });
    return 1;
});

Route::get('/test-mail-new', function() {


    $general = \App\Models\GeneralSetting::first();

    $email_template = \App\Models\EmailTemplate::where('act', "PASS_RESET_DONE")->where('email_status', 1)->first();

    $html = shortCodeReplacer("{{name}}", "Ryan", $general->email_template);
    $html = shortCodeReplacer("{{message}}", $email_template->email_body, $html);
    
    if (empty($html)) {
        $html = $email_template->email_body;
    }

// dd(config('mail'), config('mail.mailers.smtp.username'));

    \Illuminate\Support\Facades\Mail::html($html, function ($message) {
        $message->to('kingking321998@gmail.com')
            ->from(config('mail.mailers.smtp.username'), "Millionare")
            ->subject('Test');
    });
});


Route::get('/run/my/cron/{id}', 'CronController@cron')->name('bv.matching.cron');

Route::get('/get/user/data', 'SiteController@getUserData')->name('get.user.data');

Route::get('/get/user/data2', 'SiteController@getUserData2')->name('get.user.data2');

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'paypal\ProcessController@ipn')->name('paypal');
    Route::get('paypal_sdk', 'paypal_sdk\ProcessController@ipn')->name('paypal_sdk');
    Route::post('perfect_money', 'perfect_money\ProcessController@ipn')->name('perfect_money');
    Route::post('stripe', 'stripe\ProcessController@ipn')->name('stripe');
    Route::post('stripe_js', 'stripe_js\ProcessController@ipn')->name('stripe_js');
    Route::post('stripe_v3', 'stripe_v3\ProcessController@ipn')->name('stripe_v3');
    Route::post('skrill', 'skrill\ProcessController@ipn')->name('skrill');
    Route::post('paytm', 'paytm\ProcessController@ipn')->name('paytm');
    Route::post('payeer', 'payeer\ProcessController@ipn')->name('payeer');
    Route::post('paystack', 'paystack\ProcessController@ipn')->name('paystack');
    Route::post('voguepay', 'voguepay\ProcessController@ipn')->name('voguepay');
    Route::get('flutterwave/{trx}/{type}', 'flutterwave\ProcessController@ipn')->name('flutterwave');
    Route::post('razorpay', 'razorpay\ProcessController@ipn')->name('razorpay');
    Route::post('instamojo', 'instamojo\ProcessController@ipn')->name('instamojo');
    Route::get('blockchain', 'blockchain\ProcessController@ipn')->name('blockchain');
    Route::get('now_payments', 'now_payments\ProcessController@ipn')->name('now_payments');
    Route::get('blockio', 'blockio\ProcessController@ipn')->name('blockio');
    Route::post('coinpayments', 'coinpayments\ProcessController@ipn')->name('coinpayments');
    Route::post('coinpayments_fiat', 'coinpayments_fiat\ProcessController@ipn')->name('coinpayments_fiat');
    Route::post('coingate', 'coingate\ProcessController@ipn')->name('coingate');
    Route::post('coinbase_commerce', 'coinbase_commerce\ProcessController@ipn')->name('coinbase_commerce');
    Route::get('mollie', 'mollie\ProcessController@ipn')->name('mollie');
    Route::post('cashmaal', 'cashmaal\ProcessController@ipn')->name('cashmaal');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications','AdminController@notifications')->name('notifications');

        // Users Manager
        Route::post('users/data/{id}', 'ManageUsersController@usersData')->name('users.data');
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('users/activation/{id}', 'ManageUsersController@usersActivation')->name('users.activation');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.emailVerified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.emailUnverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.smsUnverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.smsVerified');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::get('user/referral/{id}', 'ManageUsersController@userRef')->name('users.ref');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.addSubBalance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('user/password/change/{id}', 'ManageUsersController@passwordChange')->name('users.password.change');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');
        Route::put('user//kyc/verify/{id}', 'ManageUsersController@verifyUpdate')->name('users.verify.update');
        Route::put('/user/plan/activation/{id}', 'ManageUsersController@planActivation')->name('user.plan.active');
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');

        Route::get('users/kyc', 'ManageUsersController@showKyc')->name('users.kyc.all');
        Route::get('/users/update/roi/{id}/{option}', 'ManageUsersController@updateRoi')->name('roi.update');

        // mlm plan
        Route::get('plans', 'MlmController@plan')->name('plan');
        Route::post('plan/store', 'MlmController@planStore')->name('plan.store');
        Route::post('plan/update', 'MlmController@planUpdate')->name('plan.update');

        // Wallets
        Route::get('wallet', 'WalletController@wallet')->name('wallet');
        Route::post('wallet/store', 'WalletController@walletStore')->name('wallet.store');
        Route::post('wallet/update', 'WalletController@walletUpdate')->name('wallet.update');
        Route::get('wallet/adjust', 'WalletController@walletAdjust')->name('wallet.adjust');
        
        // Wallets
        Route::get('epins', 'WalletController@epins')->name('epins');
        Route::post('epins/epin-update', 'WalletController@epinUpdate')->name('epin.update');

        //Ranks
        Route::get('ranks', 'RankController@ranks')->name('ranks');
        Route::post('ranks/store', 'RankController@rankStore')->name('rank.store');
        Route::post('ranks/update', 'RankController@rankUpdate')->name('rank.update');

        // Media
        Route::get('media', 'MediaController@media')->name('media');
        Route::post('media/store', 'MediaController@mediaStore')->name('media.store');
        Route::post('media/update', 'MediaController@mediaUpdate')->name('media.update');

        // Commission
        Route::get('commission/all', 'CommissionController@commission')->name('commission.all');
        Route::post('commission/store', 'CommissionController@commissionStore')->name('commission.store');
        Route::post('commission/update', 'CommissionController@commissionUpdate')->name('commission.update');
       
        Route::get('commission/detail', 'CommissionController@commissionDetail')->name('commission.detail');
        Route::post('commission//detail/store', 'CommissionController@commissionDetailStore')->name('commission.detail.store');
        Route::post('commission/detail/update', 'CommissionController@commissionDetailUpdate')->name('commission.detail.update');
        Route::post('commission/detail/delete', 'CommissionController@commissionDetailDelete')->name('commission.detail.delete');

        Route::get('commission/release', 'CommissionController@commissionRelease')->name('commission.release');
        Route::post('commission/release/store', 'CommissionController@commissionReleaseStore')->name('commission.release.store');
        Route::post('commission/release/update', 'CommissionController@commissionReleaseUpdate')->name('commission.release.update');
        Route::post('commission/release/release', 'CommissionController@commissionReleaseRelease')->name('commission.release.release');
    

        // matching bonus
        Route::post('matching-bonus/update', 'MlmController@matchingUpdate')->name('matching-bonus.update');

        // tree
        Route::get('/tree/{id}', 'ManageUsersController@tree')->name('users.single.tree');
        Route::get('/user/tree/{user}', 'ManageUsersController@otherTree')->name('users.other.tree');
        Route::get('/user/tree/search', 'ManageUsersController@otherTree')->name('users.other.tree.search');

        Route::get('notice', 'GeneralSettingController@noticeIndex')->name('setting.notice');
        Route::post('notice/update', 'GeneralSettingController@noticeUpdate')->name('setting.notice.update');
        Route::post('notice/store', 'GeneralSettingController@notificationStore')->name('setting.notice.store');
        Route::get('notice/delete/{id}', 'GeneralSettingController@notificationDelete')->name('setting.notice.delete');



        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');

        // Promotions
        Route::get('promotion','PromotionController@userPromotion')->name('promotion.index');
        Route::post('save-promotion','PromotionController@saveUserPromotion')->name('save.promotion');

        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');



            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('all_pending', 'DepositController@allPending')->name('all.pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');

        });


        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function(){
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });

        // Report

        Route::post('report/data/{id}', 'ReportController@reportsData')->name('reports.data');
        
        Route::get('report/user/wallets/{type}', 'ReportController@userWallets')->name('report.user.wallets');
        Route::post('report/user/wallet/update', 'ReportController@userWalletUpdate')->name('user.wallet.update');
        
        Route::get('report/plans/purchased/{type}', 'ReportController@planPurchased')->name('report.plan.purchased');
        Route::post('report/plan/purchased/update', 'ReportController@planPurchasedUpdate')->name('plan.purchased.update');
        Route::post('report/plan/purchased/delete', 'ReportController@planPurchasedDelete')->name('plan.purchased.delete');
        
        Route::get('report/ranks/rank-achievers', 'ManageUsersController@rankAchievers')->name('report.ranks.rankAchievers');
        
        Route::get('report/wallet', 'ReportController@wallet')->name('report.wallet');
        Route::get('report/wallet/{id}', 'ReportController@wallet')->name('report.wallets');
        Route::get('report/commission', 'ReportController@commission')->name('report.commission');
        Route::get('report/commission/{id}', 'ReportController@commission')->name('report.commissions');
        Route::post('report/commission/{id}', 'ReportController@deleteUnilevelBonus')->name('report.delete.unilevel');
        Route::post('report/commission/edit/{id}', 'ReportController@editComission')->name('report.edit.comission');
        Route::get('report/referral-commission', 'ReportController@refCom')->name('report.refCom');
        Route::get('report/binary-commission', 'ReportController@binary')->name('report.binaryCom');
        Route::get('report/roi-income', 'ReportController@roiIncome')->name('report.roi');
        Route::get('report/vip-commission', 'ReportController@vipCom')->name('report.vipCom');
        Route::get('report/invest', 'ReportController@invest')->name('report.invest');
        
        Route::get('report/epin', 'ReportController@epin')->name('report.epin');
        Route::get('report/epin/search', 'ReportController@epinSearch')->name('report.epin.search');

        Route::get('report/bv-log', 'ReportController@bvLog')->name('report.bvLog');
        Route::get('report/bv-log/{id}', 'ReportController@singleBvLog')->name('report.single.bvLog');

        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/transaction/date-search', 'ReportController@transactionDateSearch')->name('report.transaction.dateSearch');


        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');

        // Founder Bonus
        Route::get('founder-list', 'ManageUsersController@founderList')->name('founder.list');
        Route::get('get-founder-users', 'ManageUsersController@GetFounderUsers')->name('get.founders');
        Route::post('save-founder-users', 'ManageUsersController@SaveFounderUsers')->name('save.founders');

        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdatepp')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.import_lang');



        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo_icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo_icon');

        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');


        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.sendTestMail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsSetting')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.global');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.sendTestSMS');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {

            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');

            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});




/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');


    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code_verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify-code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');

            Route::get('profile-setting', 'UserController@profile')->name('profile-setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::get('change-password', 'UserController@changePassword')->name('change-password');
            Route::post('change-password', 'UserController@submitPassword');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');
            Route::get('login/history', 'UserController@userLoginHistory')->name('login.history');

            //KYC
            Route::get('/verify-kyc', 'UserController@kycVerification')->name('kyc.verify');
            Route::put('/verify-kyc', 'UserController@submitKYC');

            //Media
            Route::get('/media', 'PlanController@mediaIndex')->name('media.index');

            //plan
            Route::get('/plan', 'PlanController@planIndex')->name('plan.index');
            Route::post('/plan', 'PlanController@planStore')->name('plan.purchase');
            Route::post('/plan-upgrade', 'PlanController@planUpgrade')->name('plan.upgrade.purchase');
            Route::post('/plan-renew', 'PlanController@planRenew')->name('plan.renew');
            Route::get('/referral-log', 'UserController@referralCom')->name('referral.log');

            Route::post('/plan/storm/active', 'UserController@stormPlan')->name('storm.plan.active');

            Route::post('/roi/compound', 'PlanController@roiCompound')->name('roi.compound');

            Route::get('/binary-log', 'PlanController@binaryCom')->name('binary.log');
            Route::get('/binary-summery', 'PlanController@binarySummery')->name('binary.summery');
            Route::get('/bv-log', 'PlanController@bvlog')->name('bv.log');
            Route::get('/referrals', 'PlanController@myRefLog')->name('my.ref');
            Route::get('/tree', 'PlanController@myTree')->name('my.tree');
            Route::get('/tree/{user}', 'PlanController@otherTree')->name('other.tree');
            Route::get('/tree/search', 'PlanController@otherTree')->name('other.tree.search');

            //balance transfer
            Route::get('/transfer', 'UserController@indexTransfer')->name('balance.transfer');
            Route::post('/transfer', 'UserController@balanceTransfer')->name('balance.transfer.post');
            Route::post('/search-user', 'UserController@searchUser')->name('search.user');
            Route::post('/get/charge', 'UserController@getChargeAjax')->name('get.total.charge');


            //Report
            Route::get('report/deposit/log', 'UserReportController@depositHistory')->name('report.deposit');
            Route::get('report/invest/log', 'UserReportController@investLog')->name('report.invest');
            Route::get('report/transactions/log', 'UserReportController@transactions')->name('report.transactions');
            Route::get('report/withdraw/log', 'UserReportController@withdrawLog')->name('report.withdraw');
            Route::get('report/commission', 'UserReportController@commissions')->name('report.commission');
            Route::get('report/wallet', 'UserReportController@wallets')->name('report.wallet');
            
            //E-Wallet
            Route::get('e-wallet', 'UserController@eWallet')->name('ewallet');
            Route::post('e-wallet/buy/plan', 'UserController@buyPlanUser')->name('buy.plan.post');

            //Ranks
            Route::get('ranks', 'UserController@ranks')->name('ranks');

            // Deposit
            Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::get('deposit/history', 'UserController@depositHistory')->name('deposit.history');

            // Withdraw
            Route::get('/withdraw', 'UserController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'UserController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'UserController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'UserController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'UserController@withdrawLog')->name('withdraw.history');

        });
    });
});

Route::post('/check/referral', 'SiteController@CheckUsername')->name('check.referral');
Route::post('/get/user/position', 'SiteController@userPosition')->name('get.user.position');

Route::post('subscriber', 'SiteController@subscriberStore')->name('subscriber.store');

Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

Route::get('/blog', 'SiteController@blog')->name('blog');
Route::get('/blog/details/{slug}/{id}', 'SiteController@singleBlog')->name('singleBlog');
Route::get('/terms-conditions', 'SiteController@terms')->name('terms');

Route::get('/', 'SiteController@index')->name('home');
Route::get('/{slug}', 'SiteController@pages')->name('pages');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholderImage');
Route::get('links/{slug}', 'SiteController@links')->name('links');

Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit')->name('contact.send');
