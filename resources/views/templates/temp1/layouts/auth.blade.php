<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta setup -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo')

    <!-- Title -->
    <title> {{ $general->sitename }} - {{ __(@$page_title) }} </title>

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="{{ getImage(imagePath()['logoIcon']['path'] . '/favicon.png') }}" type="image/x-icon">

    
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'frontend/app/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'frontend/app/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'frontend/assets/font/font-awesome.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://code.highcharts.com/highcharts.js"></script>

    @stack('style-lib')
    <!-- style main css -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'frontend/app/dist/app.css') }}">

    @stack('css')

    @stack('style')
</head>

<body class="header-fixed inner-page login-page">
        <!-- preloade -->
        <div class="preloader">
            <div class="clear-loading loading-effect-2">
            <span></span>
            </div>
        </div>
        <!-- /preload -->
    <div id="wrapper">
    @stack('facebook')
    <!-- Header -->
    <header id="header_main" class="header">
        <div class="container">
            <div id="site-header-inner">
                {{-- <div class="header_logo">
                    <a href="{{ url('/') }}"><img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" width="231" height="44" alt="Millionaires Metvrse Logo"></a>
                </div> --}}
               
                {{-- <nav id="main-nav" class="main-nav">
                    <ul id="menu-primary-menu" class="menu">
                        <li class="menu-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="menu-item">
                            <a href="#about">About</a>
                        </li>
                        <li class="menu-item">
                            <a href="#products">Products</a>
                        </li>
                        <li class="menu-item ">
                            <a href="#roadmap">Roadmap</a> 
                        </li> 
                        <li class="menu-item ">
                            <a href="#contact">Contact</a> 
                        </li>
                    </ul>
                </nav><!-- /#main-nav --> --}}
                {{-- <a href="{{ route('user.login') }}" class="tf-button style1">
                    Sign In
                </a> --}}
                <div class="mobile-button"><span></span></div><!-- /.mobile-button -->
            </div>
        </div>
    </header>
    <!-- end Header -->

    <section class="page-title">
        <div class="overlay"></div> 
    </section>

    @yield('content')

    {{-- <section id="contact" class="tf-section tf_CTA">
        <div class="container relative">
            <div class="overlay">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="tf-title left mt58" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Join The <br/>Stealth Trade Bot Community Today!
                        </h2>
                        <p class="sub">Take the first step towards financial freedom in the multiverse. Join Stealth Trade Bot or contact us to learn more about our innovative products and earning opportunities.</p>
                        <div class="wrap-btn">
                            <a href="#" class="tf-button style3">
                                Join Now
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="image_cta" data-aos="fade-left" data-aos-duration="1200">
                    <img class="move4" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/img_cta.png') }}" alt="">
                  </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <div class="footer-logo">
                        <div class="logo_footers">
                            <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" width="231" height="44" alt="Millionaires Metvrse Logo">
                        </div>
                        <p class="mt-4">The Stealth Trade Bot is a cutting-edge ecosystem that offers innovative digital asset products and limitless earning opportunities. Join us to explore the potential of the multiverse and achieve financial freedom.</p>
                    </div>
                    <div class="widget">
                        
                    </div>
                    
                    <div class="widget">
                        <h5 class="widget-title">
                            Contact us
                        </h5>
                        <ul class="widget-link contact">
                            <li>
                                <p>Address</p>
                                <a href="#">124 City Road, London, United Kingdom, EC1V 2NX</a>
                            </li>
                            <li>
                                <p>Phone</p>
                                <a href="#">+44 20 3435 4354</a>
                            </li>
                            <li class="email">
                                <p>Email</p>
                                <a href="#">admin@stealthtradebot.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="wrap-fx">
                    <div class="Copyright">
                        Copyright © Stealth Trade Bot. All Rights Reserved.
                    </div>
                </div>
            </div>
            
        </div>
    </footer> --}}
    </div>
    <a id="scroll-top"></a>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/jquery.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/swiper.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/app.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/jquery.easing.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/parallax.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/count-down.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'frontend/app/js/countto.js') }}"></script>

    @stack('script-lib')

    @stack('js')
    @include('partials.notify')
    @include('partials.plugins')

    <script>
    $(document).on("change", ".langSel", function() {
        window.location.href = "{{ url('/') }}/change/" + $(this).val();
    });
    $('form').on('submit', function () {
        $('.submit-btn').attr('disabled', 'true'); 
    });
    </script>

    @stack('script')
</body>

</html>
