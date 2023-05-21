@php

$works = getContent('how_it_works.element');
$workCaption = getContent('how_it_works.content', true);
@endphp
@if ($works)

    <!-- htdowp-area start -->
    <div class="htdowp-area">
        <div class="container">
            <div class="tp_htdowp">
                <h3>@lang(@$workCaption->data_values->heading)</h3>
                <h2>@lang(@$workCaption->data_values->sub_heading)</h2>
                <p class="item1">{{ __(@$workCaption->data_values->description) }}</p>
            </div>
            <div class="row">
                @foreach ($works as $k => $data)
                    <div class="col-lg-4 col-md-4">
                        <div class="htdowp-part htdowp-part{{ $k + 1 }}">
                            <img src="{{ asset($activeTemplateTrue . 'frontend/img/icon' . ($k + 1) . '.svg') }}"
                                alt="icon" />
                            <a href="#">
                                <h3>{{ __(@$data->data_values->title) }}</h3>
                            </a>
                            <p>{{ __(@$data->data_values->description) }}</p>
                            <span></span>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- htdowp-area end -->

@endif
