@extends($activeTemplate.'layouts.master')

@section('content')

    @include($activeTemplate.'layouts.breadcrumb')

    <div class="contact-info  padding-top">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-sm-10 col-md-6 col-lg-4">
                    <div class="contact--item">
                        <div class="contact-item">
                            <div class="contact-thumb">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <div class="contact-content">
                                <h6 class="title">@lang('Email Address')</h6>
                                <ul>
                                    <li>
                                        <a href="Mailto:{{@$contact->data_values->email_address}}">{{@$contact->data_values->email_address}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-6 col-lg-4">
                    <div class="contact--item ">
                        <div class="contact-item">
                            <div class="contact-thumb">
                                <i class="fa fa-building" aria-hidden="true"></i>

                            </div>
                            <div class="contact-content">
                                <h6 class="title">@lang('Office Address')</h6>
                                <ul>
                                    <li>
                                        {{@$contact->data_values->contact_details}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-6 col-lg-4">
                    <div class="contact--item">
                        <div class="contact-item">
                            <div class="contact-thumb">
                                <i class="fa fa-phone-square" aria-hidden="true"></i>
                            </div>
                            <div class="contact-content">
                                <h6 class="title">@lang('Phone Number')</h6>
                                <ul>
                                    <li>
                                        <a href="Tel:{{@$contact->data_values->contact_number}}">{{@$contact->data_values->contact_number}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="contact-section padding-top padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-header left-style margin-olpo">
                        <h2 class="title">{{@$contact->data_values->title}}</h2>
                        <p>{{@$contact->data_values->short_details}}</p>
                    </div>
                    <form method="post" action="" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <input type="text" name="name" value="{{old('name')}}"  placeholder="@lang('Your Name')" id="name" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="text" name="subject" placeholder="@lang('Write your subject')" value="{{old('subject')}}"  id="subject" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="email" name="email"  value="{{old('email')}}" id="email" placeholder="@lang('Enter E-Mail Address')" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <textarea placeholder="@lang('Write your message')" name="message" id="message" required>{{old('message')}}</textarea>
                            </div>

                            @if(reCaptcha())
                                <div class=" col-lg-12 form-group my-3">
                                    @php echo reCaptcha(); @endphp
                                </div>
                            @endif
                            <div class=" col-lg-12 form-group my-3">
                                @include($activeTemplate.'partials.custom-captcha')
                            </div>

                            <div class="col-lg-12 form-group">
                                <input type="submit" class="cmn-btn" value="@lang('Submit Now')">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img class="wow slideInRight" src="{{getImage('assets/images/frontend/contact_us/' . @$contact->data_values->background_image, '650x780')}}" alt="contact">
                </div>
            </div>
        </div>
    </section>



@endsection
