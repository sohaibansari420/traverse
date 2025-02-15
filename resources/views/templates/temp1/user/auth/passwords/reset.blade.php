@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('user.password.update') }}">
                    @csrf
                        <div class="project-info-form forget-form">
                            <h4 class="title">Reset Password</h4> 
                            <p>Reset your account password</p>
                            <div class="form-inner"> 
                                <input type="hidden" name="email" value="{{ $email }}">
                                <input type="hidden" name="token" value="{{ $token }}">

                                <fieldset>
                                    <label >
                                        Password *
                                    </label>
                                    <input type="password" name="password" placeholder="@lang('Password')" required>
                                </fieldset> 

                                <fieldset>
                                    <label >
                                        Confirm Password *
                                    </label>
                                    <input type="password" name="password_confirmation" placeholder="@lang('Confirm Password')" required>
                                </fieldset>
                            </div>
                            <div class="bottom">
                                Nevermind. 
                                <a href="{{route('user.login')}}">Sign in</a>
                            </div>
                        </div> 

                        <div class="wrap-btn">
                            <button type="submit" class="tf-button style1">
                                Change password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
