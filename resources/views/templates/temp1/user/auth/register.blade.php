@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('user.register') }}" onsubmit="return submitUserForm();">
                        @csrf
                        <div class="project-info-form form-login style2">
                            <h6 class="title">Register</h6>
                            <h6 class="title show-mobie"><a href="{{route('user.login')}}">Login</a></h6>
                            <h6 class="title link"><a href="{{route('user.login')}}">Login</a></h6>
                            <p>Welcome to Stealth Trade Bot, please enter your details</p>
                            <div class="form-inner"> 
        
                                @if ($ref_user == null)
                                    <fieldset>
                                        <label>
                                            Referral User Name *
                                        </label>
                                        <input type="text" name="referral" class="referral" placeholder="@lang('Enter referral username')*" required autofocus>
                                    </fieldset>

                                    <fieldset>
                                        <label>
                                            Position
                                        </label>
                                        <select class="position" id="position" name="position" required>
                                            <option value="">@lang('Select position')*</option>
                                            <option value="1">@lang('Left')</option>
                                            <option value="2">@lang('Right')</option>
                                        </select>
                                    </fieldset>
                                @else
                                    <fieldset>
                                        <label>
                                            Referral User Name
                                        </label>
                                        <input type="text" name="referral" class="referral" value="{{ $ref_user->username }}" placeholder="@lang('Enter referral username')*" required readonly>
                                    </fieldset>

                                    <fieldset>
                                        <label>
                                            Position
                                        </label>
                                        <select class="position" id="position" required disabled>
                                            <option value="">@lang('Select position')*</option>
                                            @foreach (mlmPositions() as $k => $v)
                                                <option @if ($position == $k) selected @endif
                                                    value="{{ $k }}">@lang($v)</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="position" value="{{ $position }}">
                                    </fieldset>
                                @endif
                                
                                <fieldset>
                                    <label>
                                        First Name *
                                    </label>
                                    <input type="text" name="firstname" value="{{ old('firstname') }}" placeholder="@lang('Enter your first name')" autofocus required>
                                </fieldset> 

                                <fieldset>
                                    <label>
                                        Last Name *
                                    </label>
                                    <input type="text" name="lastname" value="{{ old('lastname') }}" placeholder="@lang('Enter your last name')" required>
                                </fieldset> 

                                <fieldset>
                                    <label>
                                        Mobile Number *
                                    </label>
                                    <div class="input-group input-group-custom">
                                        <div class="input-group-prepend">
                                            <select name="country_code" class="input-group-text" style="padding:0;">
                                                @include('partials.country_code')
                                            </select>
                                        </div>
                                        <input type="text" style="color: #798DA3" value="{{ old('mobile') }}" class="form-control" name="mobile" placeholder="@lang('Your Mobile Number')" required>
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <label>
                                        Country *
                                    </label>
                                    <input type="text" name="country" placeholder="@lang('Country')" readonly />
                                </fieldset> 

                                <fieldset>
                                    <label>
                                        Email *
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('Enter your email')" required>
                                </fieldset> 

                                <fieldset>
                                    <label>
                                        Legacy Email *
                                    </label>
                                    <input type="email" name="legacy_email" value="{{ old('legacy_email') }}" placeholder="@lang('Enter your Legacy email')" required>
                                </fieldset> 

                                <div class="row mb-0 d-none">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group ">
                                            <input type="text" class="form-control" name="city"
                                                placeholder="@lang('Enter your city')*" value="My City" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group ">
                                            <input type="text" class="form-control" name="state"
                                                placeholder="@lang('Enter your state')*" value="My State" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group ">
                                            <input type="text" class="form-control" name="zip"
                                                placeholder="@lang('Enter your zip')*" value="00000" required>
                                        </div>
                                    </div>
                                </div>

                                <fieldset>
                                    <label>
                                        User Name *
                                    </label>
                                    <input type="text" name="username" value="{{ old('username') }}" placeholder="@lang('Enter your username')" required>
                                </fieldset> 

                                <fieldset>
                                    <label>
                                        Password *
                                    </label>
                                    <input type="password" name="password" id="myInputTwo" placeholder="@lang('Password')">
                                </fieldset>
                                <fieldset class="mb19">
                                    <label>
                                        Confirm password *
                                    </label>
                                    <input type="password" name="password_confirmation" id="myInputTwoConfirm" placeholder="@lang('Confirm password')" required>
                                </fieldset>
                                @if (reCaptcha())
                                    <div class="form-group mb-3">
                                        @php echo reCaptcha(); @endphp
                                    </div>
                                @endif
                                @include($activeTemplate . 'partials.custom-captcha')
                                <fieldset class="checkbox"> 
                                    <input type="checkbox" id="checkbox" name="checkbox" >
                                    <label for="checkbox" class="icon"></label>
                                    <label for="checkbox">
                                        I accept the Term of Conditions and Privacy Policy
                                    </label>
                                </fieldset>
                            </div>
                        </div> 

                        <div class="wrap-btn">
                            <button type="submit" class="tf-button style2">
                                Register
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
        (function($) {
            "use strict";
            var not_select_msg = $('#position-test').html();
            $(document).on('keyup', '#ref_name', function() {
                var ref_id = $('#ref_name').val();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: "POST",
                    url: "{{ route('check.referral') }}",
                    data: {
                        'ref_id': ref_id,
                        '_token': token
                    },
                    success: function(data) {
                        if (data.success) {
                            $('select[name=position]').removeAttr('disabled');
                            $('#position-test').text('');
                        } else {
                            $('select[name=position]').attr('disabled', true);
                            $('#position-test').html(not_select_msg);
                        }
                        $("#ref").html(data.msg);
                    }
                });
            });
            $(document).on('change', '#position', function() {
                updateHand();
            });

            function updateHand() {
                var pos = $('#position').val();
                var referrer_id = $('#referrer_id').val();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: "POST",
                    url: "{{ route('get.user.position') }}",
                    data: {
                        'referrer': referrer_id,
                        'position': pos,
                        '_token': token
                    },
                    success: function(data) {
                        $("#position-test").html(data.msg);
                    }
                });
            }

            @if (@$country_code)
                $(`option[data-code={{ $country_code }}]`).attr('selected', '');
            @endif
            $('select[name=country_code]').change(function() {
                $('input[name=country]').val($('select[name=country_code] :selected').data('country'));
            }).change();

            function submitUserForm() {
                var response = grecaptcha.getResponse();
                if (response.length == 0) {
                    document.getElementById('g-recaptcha-error').innerHTML =
                        '<span style="color:red;">@lang('Captcha field is required.')</span>';
                    return false;
                }
                return true;
            }

            function verifyCaptcha() {
                document.getElementById('g-recaptcha-error').innerHTML = '';
            }

            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    var password = $(this).val();
                    var capital = /[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/;
                    var capital = capital.test(password);
                    if (!capital) {
                        $('.capital').removeClass('d-none');
                    } else {
                        $('.capital').addClass('d-none');
                    }
                    var number = /[123456790]/;
                    var number = number.test(password);
                    if (!number) {
                        $('.number').removeClass('d-none');
                    } else {
                        $('.number').addClass('d-none');
                    }
                    var special = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
                var special = special.test(password);
                if (!special) {
                    $('.special').removeClass('d-none');
                } else {
                    $('.special').addClass('d-none');
                }

            });
        @endif

        @if (old('position'))
            $(`select[name=position]`).val('{{ old('position') }}');
            @endif

        })(jQuery);
    </script>
@endpush
