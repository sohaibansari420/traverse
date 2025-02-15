@extends($activeTemplate.'layouts.master')

@section('content')

    @php
        $sliders = getContent('slider.element');
    @endphp
    <!-- START HOME -->
    <section id="home" class="home_bg">
        <video id="myVideo" onloadeddata="this.play();" playsinline loop muted>
            <source type="video/mp4" src="{{asset($activeTemplateTrue . 'frontend/bg.mp4')}}">
        </video>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-xs-12 text-center">
                    <div class="hero-text">
                    @foreach($sliders as $slider)
                        <h2>{{__(@$slider->data_values->title)}}</h2>
                        <p>{{__(@$slider->data_values->subtitle)}}</p>
                    @endforeach
                        <div class="home_btn">
                        @auth
                            <a href="{{route('user.home')}}" target="_blank" class="btn_one wow bounceIn" data-wow-delay=".6s">@lang('Dashboard')</a>
                            <a href="{{route('user.logout')}}" class="btn_two wow bounceIn" data-wow-delay=".8s">@lang('Logout')</a>
                        @else
                            <a href="{{route('user.login')}}" class="btn_one wow bounceIn" data-wow-delay=".6s">@lang('Sign In')</a>
                            <a href="{{route('user.register')}}" class="btn_two wow bounceIn" data-wow-delay=".8s">@lang('Sign Up')</a>
                        @endauth
                        </div>
                    </div>
                </div>
                <!--- END COL -->
            </div>
            <!--- END ROW -->
        </div>
        <!--- END CONTAINER -->
    </section>
    <!-- END  HOME -->

    <section id="about" class="about_us section-padding">
        <canvas id="projector">Your browser does not support the Canvas element.</canvas>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
                    <div class="about-text">
                        <p>@php echo $terms->data_values->description; @endphp</p>
                    </div>
                </div>
                <!--- END COL -->
            </div>
            <!--- END ROW -->
        </div>
        <!--- END CONTAINER -->
    </section>

@endsection






