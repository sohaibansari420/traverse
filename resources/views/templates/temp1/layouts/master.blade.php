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

<body class="header-fixed main home1 counter-scroll">
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
                <div class="header_logo">
                    <a href="{{ url('/') }}"><img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" width="231" height="44" alt="Stealth Trade Bot Logo"></a>
                </div>
               
                <nav id="main-nav" class="main-nav">
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
                </nav><!-- /#main-nav -->
                <a href="{{ route('user.login') }}" class="tf-button style1">
                    Sign In
                </a>
                <div class="mobile-button"><span></span></div><!-- /.mobile-button -->
            </div>
        </div>
    </header>
    <!-- end Header -->

    <section class="page-title">
        <div class="icon_bg">
            <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/backgroup/bg_inner_slider.png') }}" alt="">
        </div>
        <div class="swiper-container slider-main">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="slider-st1">
                        <div class="overlay">
                            <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/backgroup/bg-slider6.png') }}" alt="">
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-slider">
                                        <div class="content-box">
                                            <h1 class="title" >Discover The <br/>Stealth Trade Bot</h1>
                                            <p class="sub-title">Join our innovative platform today and explore the limitless possibilities of the multiverse</p>
                                            <div class="wrap-btn">
                                                <a href="{{route('user.register')}}" class="tf-button style2">
                                                    Join Now
                                                </a>
                                            </div>
                                        </div>
                                        <div class="image">
                                            <img class="img_main" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/slider/Furore.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tf-section project_3 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tf-title" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Easy to join with 3 steps
                        </h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="project-box-style2_wrapper">
                        <div class="project-box-style2" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
                            <div class="image">
                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_5.png') }}" alt="">
                            </div>
                            <div class="content">
                                <h5>Sign Up</h5>
                                <p class="desc">Visit our website and click on the "Join Now" button to sign up. Fill in your personal details, including your name, email address, and a strong password.</p>
                                <p class="number">01</p>
                            </div>
                            <a href="#" class="btn_project">
                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/button_project.svg') }}" alt="">
                            </a>
                        </div>
                        <div class="project-box-style2" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
                            <div class="image">
                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_6.png') }}" alt="">
                            </div>
                            <div class="content">
                                <h5>Choose your Package</h5>
                                <p class="desc">Select the package that best suits your needs and budget. We offer a range of packages to choose from, each with its own unique features and benefits.</p>
                                <p class="number">02</p>
                            </div>
                            <a href="#" class="btn_project">
                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/button_project.svg') }}" alt="">
                            </a>
                        </div>
                        <div class="project-box-style2" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
                            <div class="image">
                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_7.png') }}" alt="">
                            </div>
                            <div class="content">
                                <h5>Start Earning</h5>
                                <p class="desc">Start earning and exploring the limitless potential of the multiverse with The Stealth Trade Bot.</p>
                                <p class="number">03</p>
                            </div>
                            <a href="#" class="btn_project">
                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/button_project.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="tf-section technology">
        <div class="container w_1490">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="tf-title" data-aos="fade-right" data-aos-duration="800">
                        <div class="img_technology">
                            <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/img_technology1.png') }}" alt="">
                            <img class="coin coin_1" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/coin1.png') }}" alt="">
                            <img class="coin coin_2" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/coin2.png') }}" alt="">
                            <img class="coin coin_3" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/coin3.png') }}" alt="">
                            <img class="coin coin_4" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/coin4.png') }}" alt="">
                            <img class="coin coin_5" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/coin5.png') }}" alt="">
                            <img class="coin coin_6" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/coin6.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="content_technology" data-aos="fade-up" data-aos-duration="800">
                        <div class="tf-title left">
                            <h2 class="title mb20">
                                Explore the Limitless Possibilities of Stealth Trade Bot
                            </h2>
                        </div>
                        <p class="sub_technology">Stealth Trade Bot is a blockchain-powered platform that offers an immersive and seamless experience across a range of applications. Our ecosystem includes an OTT platform, 3D NFT game, Online shopping mall application, and Crypto Currency Exchange. Our goal is to create a unique and inclusive entertainment experience that is accessible to everyone. We have a team of experts in gaming, blockchain, and entertainment, who are passionate about innovation and pushing the boundaries of what is possible in the Multiverse. Whether you want to play a 3D NFT game, shop for physical goods using cryptocurrency, or exchange cryptocurrencies with other users, Stealth Trade Bot has exciting applications that are sure to keep you engaged and entertained. Join us on our journey to redefine the future of entertainment in the Multiverse. With Stealth Trade Bot, the possibilities are endless.
                        </p>
                        {{-- <img class="move4 mt-4 mb-4" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/site/legal.png') }}" alt="Legal Document"> --}}
                        <div class="swiper-container slider-6">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_1.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_2.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_3.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_4.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_5.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_6.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_4.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_5.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_6.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_1.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_2.png') }}" alt="">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/logo_tech_3.png') }}" alt="">
                                </div>
                            </div>
                            <div class="swiper-pagination pagination_slider-6"></div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    
    

    <section class="tf-section tf_CTA">
        <div class="overlay">
            <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/backgroup/bg_team_section.png') }}" alt="">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="tf-title left mt58" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            CEO
                        </h2>
                        <h6>Dear Stealth Trader Community,</h6>
                        <p>Welcome to Stealth Traderbot—where innovation meets opportunity in the world of automated trading. Our mission is to empower you with cutting-edge technology that enhances your trading experience, maximizes profitability, and simplifies the complexities of the market.</p>
                        <p>In today’s fast-paced financial landscape, success requires agility, precision, and the right tools. That’s why we’ve developed a robust ecosystem of AI-driven trading bots designed to help you capitalize on opportunities 24/7. Whether you’re a seasoned trader or just starting out, Stealth Traderbot provides the resources you need to grow and thrive.</p>
                        <p>But beyond technology, what truly sets us apart is our community. We are a collective of visionaries, entrepreneurs, and traders who believe in financial freedom through smart, automated solutions. Your success is our success, and we are committed to continuously improving our platform to serve you better.</p>
                        <p>Thank you for being part of this journey. We’re excited for what the future holds, and together, we will redefine the way trading is done.</p>
                        <div class="wrap-btn">
                            <a href="{{route('user.register')}}" class="tf-button style3">
                                Join Now
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                  <div class="image_cta mt-2" data-aos="fade-left" data-aos-duration="1200">
                    <img class="move4" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/ceo.png') }}" alt="CEO">
                  </div>
                </div>
                
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                  <div class="image_cta mt-2" data-aos="fade-left" data-aos-duration="1200">
                    <img class="move4" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/director.png') }}" alt="DIRECTOR">
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="tf-title left mt58" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Anderson Chester
                        </h2>
                        <h6>Director of Business Development | Stealth Traderbot</h6>
                        <p>As the Director of Business Development at Stealth Traderbot, Anderson Chester is at the forefront of driving growth, strategic partnerships, and market expansion. With a keen eye for emerging opportunities, he is committed to positioning Stealth Traderbot as a leader in automated trading solutions.</p>
                        <p>Anderson brings a wealth of experience in business growth, cryptocurrency trading, and financial technology, leveraging innovative strategies to scale operations and enhance user engagement. His focus is on developing strong industry relationships, optimizing revenue streams, and ensuring that Stealth Traderbot delivers cutting-edge trading solutions to its clients.</p>
                        <p>Passionate about empowering traders and investors, Anderson is dedicated to making automated trading more accessible, efficient, and profitable. Under his leadership, Stealth Traderbot continues to evolve, offering advanced trading tools that redefine market possibilities.</p>
                        <div class="wrap-btn">
                            <a href="{{route('user.register')}}" class="tf-button style3">
                                Join Now
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="tf-title left mt58" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Clive Williams
                        </h2>
                        <h6>Vice President of Sales, StealthBotTrader</h6>
                        <p>Clive Williams serves as the Vice President of Sales at StealthBotTrader, bringing a wealth of experience in strategic sales, business development, and leadership. With a strong background in financial technology and trading, Clive plays a pivotal role in expanding StealthBotTrader’s reach, driving growth, and empowering individuals to capitalize on arbitrage trading opportunities.</p>
                        <p>His expertise in sales strategy, client relations, and market expansion has positioned him as a key figure in the company’s mission to revolutionize automated trading solutions. Clive is passionate about helping people achieve financial success, leveraging cutting-edge technology to create new income streams.</p>
                        <p>Under his leadership, StealthBotTrader continues to thrive as a premier platform for traders looking to maximize profits with precision and efficiency.</p>
                        <div class="wrap-btn">
                            <a href="{{route('user.register')}}" class="tf-button style3">
                                Join Now
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                  <div class="image_cta mt-2" data-aos="fade-left" data-aos-duration="1200">
                    <img class="move4" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/vice_president.png') }}" alt="VICEPRESIDENT">
                  </div>
                </div>
                
            </div>
        </div>
    </section>

    <section id="products" class="tf-section project_4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tf-title center" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Stealth Trade Bot Products
                        </h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="project-box-style3_wrapper">
                        <div class="project-box-style3" data-aos="fade-in" data-aos-duration="800">
                            <div class="header_project">
                                <div class="image">
                                    <img class="mask" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_8.png') }}" alt="">
                                    <div class="shape">
                                        <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/shape_2.png') }}" alt="">
                                    </div>
                                </div>
                                <h5 class="heading"><a href="#">S Token</a></h5>
                            </div>
                            <div class="content">
                                <p>S Token is a cryptocurrency built on the Binance Smart Chain using the BEP-20 token protocol. As the native token of the Stealth Trade Bot ecosystem, it serves as a medium of exchange and store of value for users within the platform. S can be used to purchase virtual land, goods, and services within the Multiverse, as well as participate in community governance and decision-making processes. The token is designed to be deflationary, with a maximum supply of 100 million and regular burn events to decrease the circulating supply over time. S Token also offers staking and yield farming opportunities, allowing users to earn rewards for providing liquidity and participating in the ecosystem.</p>
                            </div>
                        </div>
                        {{-- <div class="project-box-style3" data-aos="fade-in" data-aos-duration="800">
                            <div class="header_project">
                                <div class="image">
                                    <img class="mask" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_555.png') }}" alt="">
                                    <div class="shape">
                                        <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/shape_2.png') }}" alt="">
                                    </div>
                                </div>
                                <h5 class="heading"><a href="#">Online Shopping Mall</a></h5>
                            </div>
                            <div class="content">
                                <p>The online shopping mall application in Stealth Trade Bot is a state-of-the-art e-commerce platform that allows users to browse and purchase products from a wide range of merchants. The platform is designed to be user-friendly and intuitive, with features like personalized recommendations, easy checkout, and secure payment processing. The application is integrated with the S Token, allowing users to make purchases using the cryptocurrency.</p>
                            </div>
                        </div> --}}
                        {{-- <div class="project-box-style3" data-aos="fade-in" data-aos-duration="800">
                            <div class="header_project">
                                <div class="image">
                                    <img class="mask" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_222.png') }}" alt="">
                                    <div class="shape">
                                        <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/shape_2.png') }}" alt="">
                                    </div>
                                </div>
                                <h5 class="heading"><a href="#">OTT Platform</a></h5>
                            </div>
                            <div class="content">
                                <p>The OTT (Over-The-Top) platform in Stealth Trade Bot is a streaming service that provides users with access to a wide range of movies, TV shows, and other video content. It uses advanced streaming technology to deliver high-quality video content to users, and it is designed to be fast, reliable, and easy to use. The platform also includes features like personalized recommendations, user profiles, and social sharing, making it a truly immersive entertainment experience.</p>
                            </div>
                        </div> --}}
                        <div class="project-box-style3" data-aos="fade-in" data-aos-duration="800">
                            <div class="header_project">
                                <div class="image">
                                    <img class="mask" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_444.png') }}" alt="">
                                    <div class="shape">
                                        <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/shape_2.png') }}" alt="">
                                    </div>
                                </div>
                                <h5 class="heading"><a href="#">3D NFT Multiverse Game</a></h5>
                            </div>
                            <div class="content">
                                <p>The 3D NFT Multiverse Game in Stealth Trade Bot is a cutting-edge gaming platform that allows users to enter a fully immersive virtual world where they can interact with other players, explore new environments, and complete quests and challenges. The game uses NFTs (Non-Fungible Tokens) to provide unique and collectible in-game items, such as weapons, armor, and special abilities, which players can use to enhance their gaming experience. The game is built on the latest blockchain technology, providing users with a secure and transparent gaming environment.</p>
                            </div>
                        </div>
                        <div class="project-box-style3" data-aos="fade-in" data-aos-duration="800">
                            <div class="header_project">
                                <div class="image">
                                    <img class="mask" src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/project_333.png') }}" alt="">
                                    <div class="shape">
                                        <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/shape_2.png') }}" alt="">
                                    </div>
                                </div>
                                <h5 class="heading"><a href="#">Crypto Currency Exchange</a></h5>
                            </div>
                            <div class="content">
                                <p>The crypto currency exchange in Stealth Trade Bot is a platform that allows users to buy, sell, and trade cryptocurrencies like Bitcoin, Ethereum, and MM. The exchange is built on the latest blockchain technology, providing users with a secure and transparent trading environment. It is designed to be user-friendly, with features like advanced charting, real-time market data, and customizable trading interfaces. The exchange is integrated with the S Token, allowing users to trade cryptocurrencies using the Stealth Trade Bot's native cryptocurrency.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="roadmap" class="tf-section roadmap">
        <div class="container w_1850">
            <div class="row">
                <div class="col-md-12">
                    <div class="tf-title" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Roadmap
                        </h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="container_inner roadmap_boder">
                        <div class="roadmap-wrapper" data-aos="fade-in" data-aos-duration="1000">
                            <div class="swiper-container slider-6">
                                <div class="swiper-wrapper">
                                    {{-- <div class="swiper-slide">
                                        <div class="roadmap-box active">
                                            <div class="icon">
                                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/icon_roadmap.svg') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h6 class="date">Q3, 2023</h6>
                                                <ul>
                                                    <li><h6>Launching</h6></li>
                                                    <li>Launch of Stealth Trade Bot</li>
                                                    <li>Expansion of network marketing team/li>
                                                    <li>Launch of new promotional campaigns and incentives for network marketers</li>
                                                    <li>Continued expansion and growth of the network marketing team</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="swiper-slide">
                                        <div class="roadmap-box">
                                            <div class="icon">
                                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/icon_roadmap.svg') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h6 class="date">Q3, 2025</h6>
                                                <ul>
                                                    <li><h6>S Token</h6></li>
                                                    <li>Launch of the S</li>
                                                    <li>Token on Binance</li>
                                                    <li>Smart Chain(BSC)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="swiper-slide">
                                        <div class="roadmap-box">
                                            <div class="icon">
                                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/icon_roadmap.svg') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h6 class="date">Q4, 2024</h6>
                                                <ul>
                                                    <li><h6>OTT Platform</h6></li>
                                                    <li>Release of the</li>
                                                    <li>Stealth Trade Botl</li>
                                                    <li>OTT platform</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="swiper-slide">
                                        <div class="roadmap-box">
                                            <div class="icon">
                                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/icon_roadmap.svg') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h6 class="date">Q2, 2025</h6>
                                                <ul>
                                                    <li><h6>Online Shopping Mall</h6></li>
                                                    <li>Release of the online</li>
                                                    <li>Shopping Mall Application</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="swiper-slide">
                                        <div class="roadmap-box">
                                            <div class="icon">
                                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/icon_roadmap.svg') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h6 class="date">Q2, 2026</h6>
                                                <ul>
                                                    <li><h6>Crypto Currency Exchange</h6></li>
                                                    <li>Launch of the dedicated crypto</li>
                                                    <li>currency exchange within</li>
                                                    <li>Stealth Trade Bot</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="roadmap-box">
                                            <div class="icon">
                                                <img src="{{ asset($activeTemplateTrue . 'frontend/assets/images/common/icon_roadmap.svg') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h6 class="date">Q1, 2027</h6>
                                                <ul>
                                                    <li><h6>3D NFT Game</h6></li>
                                                    <li>Launch of the 3D</li> 
                                                    <li>NFT Multiverse Game</li>
                                                    <li>on the S Platform</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                   
                            </div>
                          
                        </div>
                        <div class="next_slider-7 next_slider"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.5 8H16.5M16.5 8L9.75 1.25M16.5 8L9.75 14.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </div>
                        <div class="prev_slider-7 prev_slider"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5 8H1.5M1.5 8L8.25 1.25M1.5 8L8.25 14.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="tf-section tf_CTA">
        <div class="container relative">
            <div class="overlay">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="tf-title left mt58" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="title">
                            Join The <br/>Stealth Trade Bot Community Today!
                        </h2>
                        <p class="sub">Take the first step towards financial freedom in the Multiverse. Join Stealth Trade Bot or contact us to learn more about our innovative products and earning opportunities.</p>
                        <div class="wrap-btn">
                            <a href="{{route('user.register')}}" class="tf-button style3">
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
                        <p class="mt-4">The Stealth Trade Bot is a cutting-edge ecosystem that offers innovative digital asset products and limitless earning opportunities. Join us to explore the potential of the Multiverse and achieve financial freedom.</p>
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
    </footer>
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
    </script>

    @stack('script')
</body>

</html>