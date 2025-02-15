@extends($activeTemplate . 'layouts.master')

@section('contents')
    <div class="col-xl-7 col-lg-6 col-md-12">
        <img src="{{ asset($activeTemplateTrue . 'dashboard/images/svgs/authentication.svg') }}"
            class="construction-img mb-7 h-480  mt-5 mt-xl-0" alt="Info Image">
    </div>
    <div class="col-xl-5  col-lg-6 col-md-12 ">
        <div class="col-lg-11">
            <a href="{{ url('/') }}" class="light-view"><img
                    src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" class="header-brand-img mb-4"
                    alt=" logo"></a>
            <div class="wrapper wrapper2">
                <form id="login" class="card-body" tabindex="500" method="post"
                    action="{{ route('user.verify_sms') }}">
                    @csrf
                    <h2 class="mb-1 font-weight-semibold">@lang('SMS Verification')</h2>
                    <p class="mb-1">Please verify your mobile number to get started</p>
                    <p class="mb-6">@lang('Your Mobile Number:') <strong>{{ auth()->user()->mobile }}</p>

                    <div class="input-group mb-3">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        <input type="text" class="form-control" name="sms_verified_code" placeholder="@lang('Type Here...')"
                            required>
                    </div>

                    <div class="row mb-0">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">@lang('Submit')</button>
                        </div>
                        <div class="col-12 mt-2">
                            <a class="btn btn-danger btn-block"
                                href="{{ route('user.send_verify_code') }}?type=phone">@lang('Resend code')</a>
                        </div>
                        <div class="col-12 mb-0">
                            <p class="mt-3 mb-0">@lang('Please check including your Spam Folder. if not found, you can') <a href="{{ route('ticket') }}"
                                    class="text-primary ml-1">@lang('Contact Support Team')</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
