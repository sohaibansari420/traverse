@php
$subscribe = getContent('subscribe.content', true);
$items = getContent('subscribe.element');
$contact = App\Models\Frontend::where('data_keys', 'contact_us.content')->first();
@endphp
<!-- afes-area start -->
<div class="afes-area">
    <div class="container">
        <span class="span_b5"></span>
        <div class="row">
            @foreach ($items as $item)
                <div class="col-lg-8 col-md-8">
                    <div class="afes-left">
                        <h3>{{ __(@$item->data_values->title) }}</h3>
                        <p class="item1">{{ __(@$item->data_values->description) }}</p>
                        <a href="#">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="afes-right">
                        <img src="{{ getImage('assets/images/frontend/subscribe/' . @$item->data_values->image) }}"
                            alt="images not found" />
                    </div>
                </div>
            @endforeach
        </div>
        <img src="{{ asset($activeTemplateTrue . 'frontend/img/circal.svg') }}" alt="images not found" class="img_a1">
    </div>
</div>
<!-- afes-area end -->

<!-- footer-area start -->
<footer class="footer-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="footer-left">
                    <a href="#">
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo" />
                    </a>
                    <ul>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Careers </a></li>
                        <li><a href="#">Press </a></li>
                        <li><a href="#">Customer Care </a></li>
                        <li><a href="#">Services </a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="footer-right">
                    <h2>@lang(@$subscribe->data_values->heading)</h2>
                    <p>@lang(@$subscribe->data_values->sub_heading)</p>
                    <form class="subscribe-form form" method="post" action="{{ route('subscriber.store') }}">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email address" required />
                        <input type="submit" value="Subscribe">
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area end -->
