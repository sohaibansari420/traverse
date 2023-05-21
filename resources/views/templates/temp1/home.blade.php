@extends($activeTemplate . 'layouts.master')

@section('content')

    @php
        $sliders = getContent('slider.element');
    @endphp
    <!-- hero-area start -->
    @foreach ($sliders as $slider)
        <div class="hero-area"
            style="background-image: url('{{ getImage('assets/images/frontend/slider/' . @$slider->data_values->background_image, '1920x850') }}');">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12">
                        <div class="hero-part">

                            <h1>{{ __(@$slider->data_values->title) }}</h1>
                            <h3>{{ __(@$slider->data_values->subtitle) }}</h3>

                            @auth
                                <a href="{{ route('user.home') }}">@lang('Dashboard')</a>
                            @else
                                <a href="{{ route('user.register') }}">Get Started</a>
                            @endauth

                        </div>
                    </div>
                    <div class="col-lg-5">
                    </div>
                </div>
            </div>
            <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 15.png') }}" alt="" class="img_b8">
        </div>
    @endforeach
    <!-- hero-area end -->

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
