@php
    $breadcrumb = getContent('breadcrumb.content',true);
@endphp

<section class="page-header bg_img"
         data-background="{{getImage('assets/images/frontend/breadcrumb/' . @$breadcrumb->data_values->background_image, '1920x600')}}">
    <div class="container">
        <div class="page-header-wrapper">
            <h2 class="title">@lang($page_title)</h2>
            <ul class="breadcrumb">
                <li><a href="{{url('/')}}">@lang('Home')</a></li>
                <li>@lang($page_title)</li>
            </ul>
        </div>
    </div>
</section>
