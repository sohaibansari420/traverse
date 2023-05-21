@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form id="login" method="post" action="{{ route('user.login') }}" onsubmit="return submitUserForm();">
                        @csrf
                        <div class="project-info-form form-login">
                            <h6 class="title">Login</h6>
                            <h6 class="title show-mobie"><a href="{{route('user.register')}}">Register</a></h6>
                            <h6 class="title link"><a href="{{route('user.register')}}">Register</a></h6>
                            <p>Enter your credentials to access your account</p>
                            <div class="form-inner"> 
                                <fieldset>
                                    <label>
                                        User Name *
                                    </label>
                                    <input type="text" name="username" value="{{ old('username') }}" placeholder="@lang('Your Username')" required>
                                </fieldset>
                                <fieldset>
                                    <label>
                                        Password *
                                    </label>
                                    <input type="password" id="myInputThree" name="password" placeholder="@lang('Your Password')" required>
                                </fieldset> 
                                @if (reCaptcha())
                                    <div class="form-group mb-3">
                                        @php echo reCaptcha(); @endphp
                                    </div>
                                @endif
                                @include($activeTemplate . 'partials.custom-captcha')
                            </div>
                            <a href="{{ route('user.password.request') }}" class="fogot-pass">Fogot password?</a>
                        </div> 

                        <div class="wrap-btn">
                            <button type="submit" class="tf-button style2 submit-btn">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang('Captcha field is required.')</span>';
                return false;
            }
            return true;
        }

        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
@endpush
