@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">Compensation Plan</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($medias as $media)
                            <div class="col-xl-4 wow fadeInUp" data-wow-delay="1.2s">
                                <div class="card overflow-hidden">
                                    <div class="text-center p-3">
                                        <img src="{{ asset('assets/images/media/') . '/' . $media->image }}" width="360px" height="180px" />
                                    </div>
                                    <div class="card-footer border-0 mt-0 text-center">		
                                        <div class="row">
                                            <a href="{{ asset('assets/images/media/files') . '/' . $media->media }}" target="_blank" class="btn btn-primary btn-sm btn-block p-3" >Download</a>
                                        </div>						
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
