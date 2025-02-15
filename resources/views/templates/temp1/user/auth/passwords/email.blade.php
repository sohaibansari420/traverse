@extends($activeTemplate . 'layouts.auth')

@section('content')
    <section class="tf-section project-info">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('user.password.email') }}">
                    @csrf
                        <div class="project-info-form forget-form">
                            <h4 class="title">Forget Password</h4> 
                            <p>enter your email address or username in the form below and we will send you further instructions on how to reset your password</p>
                            <div class="form-inner"> 
                                <fieldset>
                                    <label >
                                        Select Type
                                    </label>
                                    <select name="type">
                                        <option value="email">@lang('E-Mail Address')</option>
                                        <option value="username">@lang('Username')</option>
                                    </select>
                                </fieldset> 

                                <fieldset>
                                    <label >
                                        Email / User Name
                                    </label>
                                    <input type="text" name="value" value="{{ old('value') }}" placeholder="@lang('Type Here...')" required autofocus="off">
                                    @error('value')
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

@push('script')
    <script type="text/javascript">
        $('select[name=type]').change(function() {
            $('.my_value').text($('select[name=type] :selected').text());
        }).change();
    </script>
@endpush
