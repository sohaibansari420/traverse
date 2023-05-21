@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('user.verify_email') }}">
                    @csrf
                        <div class="project-info-form forget-form">
                            <h4 class="title">@lang('Email Verification')</h4> 
                            <p>Kindly verify your email address <strong>{{ auth()->user()->email }}</strong></p>
                            <div class="form-inner"> 

                                <fieldset>
                                    <label >
                                        Verification Code
                                    </label>
                                    <input type="text" name="email_verified_code" placeholder="@lang('Type Here...')" required>
                                    @error('email_verified_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </fieldset> 
                            </div>
                            <div class="bottom">
                                @lang('Please check including your Junk/Spam Folder. if not found, you can') <a href="{{ route('ticket') }}">@lang('Contact Support Team')</a>
                            </div>
                        </div> 

                        <div class="wrap-btn">
                            <button type="submit" class="tf-button style2">
                                Submit Verification
                            </button>
                            <a href="{{ route('user.send_verify_code') }}?type=email" class="tf-button style1">
                                Resend Code
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection