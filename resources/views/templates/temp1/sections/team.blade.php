@php

$teamTitle = getContent('team.content', true);
$teams = getContent('team.element');
@endphp

<!-- ceo-area start -->
<div class="ceo-area">
    <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 14.png') }}" alt="" class="img_b7">

    <div class="container">
        <div class="row align-items-center ">
            <div class="col-lg-7 col-md-7">
                <div class="ceo-part">
                    <h2>@lang(@$teamTitle->data_values->heading)</h2>
                    @php echo @$teamTitle->data_values->description; @endphp
                </div>
            </div>
            <div class="col-lg-5 col-md-5">
                <div class="ceo-part">
                    @foreach ($teams as $team)
                        <img src="{{ getImage('assets/images/frontend/team/' . @$team->data_values->image) }}"
                            alt="">
                    @endforeach
                </div>
            </div>
        </div>
        <span class="span_b3"></span>
    </div>
    <img src="{{ asset($activeTemplateTrue . 'frontend/img/Ellipse 5.png') }}" alt="" class="img_b5" />
</div>
<!-- about-area end -->
