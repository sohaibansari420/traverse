@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('user.go2fa.verify') }}">
                    @csrf
                        <div class="project-info-form forget-form">
                            <h4 class="title">@lang('2FA Verification')</h4> 
                            <p>Provide 2FA Code Verification to get Sign In</p>
                            <div class="form-inner"> 

                                <fieldset>
                                    <label >
                                        2FA Code
                                    </label>
                                    <input type="text" name="code" placeholder="@lang('Type Here...')" required>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </fieldset> 
                            </div>
                            <div class="bottom">
                                Having some issue in 2FA? <a href="{{ route('ticket') }}">@lang('Contact Support Team')</a>
                            </div>
                        </div> 

                        <div class="wrap-btn">
                            <button type="submit" class="tf-button style2">
                                Submit Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
