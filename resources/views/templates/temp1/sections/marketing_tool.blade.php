@php
$marketing_tools = getContent('marketing_tool.element');
@endphp
@if ($marketing_tools)
    <!-- dnmbg-area start -->
    <div class="dnmbg-area">
        <div class="container">
            <span class="dnmbgspan_a1"></span>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="tp_dnmbg">
                        <div class="row">
                            @foreach ($marketing_tools as $marketing_tool)
                                <div class="col-lg-4 col-md-4">
                                    <div class="dnmbg-part {{ __(@$marketing_tool->data_values->class) }}">
                                        <img src="{{ getImage('assets/images/frontend/marketing_tool/' . @$marketing_tool->data_values->image, '420x420') }}"
                                            alt="images not found" />
                                        <a href="#">
                                            <h3>{{ __(@$marketing_tool->data_values->title) }}</h3>
                                        </a>
                                        <p>@php echo @$marketing_tool->data_values->description; @endphp</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <span class="dnmbgspan_a2"></span>
        </div>
    </div>
    <!-- dnmbg-area end -->
@endif
