@php
$aboutCaption = getContent('about.content', true);
@endphp
@if ($aboutCaption)
    <!-- about-area start -->
    <div class="about-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="about-part">
                        <img src="{{ getImage('assets/images/frontend/about/' . @$aboutCaption->data_values->background_image, '700x567') }}"
                            alt="images not found" />
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="about-part">
                        <h2>{{ __(@$aboutCaption->data_values->heading) }}</h2>
                        @php echo @$aboutCaption->data_values->description; @endphp
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 13.png') }}" alt="" class="img_b6">
    </div>
    <!-- about-area end -->
@endif
