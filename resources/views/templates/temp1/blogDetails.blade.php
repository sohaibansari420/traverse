@extends($activeTemplate.'layouts.master')

@section('content')

    @include($activeTemplate.'layouts.breadcrumb')



    <section class="blog-section padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between">
                <div class="col-lg-7 col-xl-8 mb-5 mb-md-3 mb-lg-0">
                    <div class="post-item post-details">
                        <div class="post-thumb c-thumb">
                            <img
                                src="{{ getImage('assets/images/frontend/blog/'.$blog->data_values->blog_image, '770x520') }}"
                                alt="blog">
                        </div>
                        <div class="post-content">
                            <h5 class="title">{{ __($blog->data_values->title) }}</h5>
                            <ul class="meta-post justify-content-start">

                                <li>
                                    <i class="fas fa-calendar-day"></i><span>{{showDateTime($blog->created_at,  $format = 'd F, Y')}}</span>
                                </li>
                            </ul>
                            <div class="entry-content">
                                <p>@php echo $blog->data_values->description; @endphp</p>

                            </div>
                        </div>

                    </div>
                    <div class="comments-area">
                        <div class="fb-comments" data-width="100%"
                             data-numposts="5"></div>
                    </div>


                </div>
                <div class="col-md-7 col-lg-5 col-xl-4">
                    <aside class="blog-sidebar">
                        <div class="widget widget-post">
                            <h6 class="title"><i class="flaticon-scroll"></i>@lang('Latest Blog')</h6>
                            <ul>
                                @foreach($latestBlogs as $latestBlog)
                                    <li>
                                        <a href="{{route('singleBlog', [slug(@$latestBlog->data_values->title), $latestBlog])}}">
                                            <div class="thumb">
                                                <img src="{{ getImage('assets/images/frontend/blog/thumb_'.@$latestBlog->data_values->blog_image, '410x410') }}" alt="blog">

                                            </div>
                                            <div class="content">
                                                <h6 class="subtitle"> {{__(str_limit(@$latestBlog->data_values->title, 60))}}</h6>
                                                <span>{{showDateTime(@$latestBlog->created_at,  $format = 'd F, Y')}}</span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('script')

    @php echo fbcomment() @endphp
@endpush








