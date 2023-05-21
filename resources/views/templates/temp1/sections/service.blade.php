@php
$serviceCaption = getContent('service.content', true);
$services = getContent('service.element');
@endphp
@if ($services)

    <!-- ourproject-area start -->
    <div class="ourproject-area">
        <div class="container">
            <div class="tp-ourproject">
                <h2>@lang(@$serviceCaption->data_values->heading)</h2>
                <p>@lang(@$serviceCaption->data_values->sub_heading)</p>
            </div>
            <div class="row no-gutters">
                <div class="col-lg-12 col-md-12">
                    @foreach ($services as $k => $data)
                        @if (($k + 1) % 2 == 0)
                            <div class="ourproject-part itemb1">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="ourproject-part-right">
                                            <h2>{{ __(@$data->data_values->title) }}</h2>
                                            @php echo @$data->data_values->description; @endphp
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="ourproject-part-left">
                                            <img src="{{ getImage('assets/images/frontend/service/' . @$data->data_values->image) }}"
                                                alt="images not found" />
                                        </div>
                                    </div>
                                </div>
                                <span class="span_a1"></span>
                            </div>
                        @else
                            <div class="ourproject-part">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="ourproject-part-left">
                                            <img src="{{ getImage('assets/images/frontend/service/' . @$data->data_values->image) }}"
                                                alt="images not found" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="ourproject-part-right">
                                            <h2>{{ __(@$data->data_values->title) }}</h2>
                                            @php echo @$data->data_values->description; @endphp
                                        </div>
                                    </div>
                                </div>
                                <span class="span_a1"></span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 11.png') }}" alt="..." class="img_b1" />
        <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 10.png') }}" alt="..." class="img_b2" />
        <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 4.png') }}" alt="..." class="img_b3" />
        <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 9.png') }}" alt="..." class="img_b4" />
    </div>
    <!-- ourproject-area end -->
@endif
