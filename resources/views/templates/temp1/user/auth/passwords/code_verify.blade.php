@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('user.password.verify-code') }}">
                    @csrf
                        <div class="project-info-form forget-form">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <h4 class="title">Verify Code</h4> 
                            <p>Find email and verify code to rest password</p>
                            <div class="form-inner"> 

                                <fieldset>
                                    <label >
                                        Code
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
                                Nevermind. 
                                <a href="{{route('user.login')}}">Sign in</a>
                            </div>
                        </div> 

                        <div class="wrap-btn">
                            <button type="submit" class="tf-button style1">
                                Reset password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
