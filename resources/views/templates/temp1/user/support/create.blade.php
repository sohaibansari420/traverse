@extends($activeTemplate . 'user.layouts.app')
@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">@lang('Submit New Ticket Request')</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data"
                        onsubmit="return submitUserForm();">
                        @csrf
                        <div class="row ">
                            <div class="form-group col-md-6 hide">
                                <label for="name">@lang('Name')</label>
                                <input type="text" name="name"
                                    value="{{ @$user->firstname . ' ' . @$user->lastname }}"
                                    class="form-control form-control-lg" placeholder="@lang('Enter Name')" readonly>
                            </div>
                            <div class="form-group col-md-6 hide">
                                <label for="email">@lang('Email address')</label>
                                <input type="email" name="email" value="{{ @$user->email }}"
                                    class="form-control form-control-lg" placeholder="@lang('Enter your Email')" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="website">@lang('Subject')</label>
                                <input type="text" name="subject" value="{{ old('subject') }}"
                                    class="form-control form-control-lg" placeholder="@lang('Subject')" autofocus>
                            </div>
                            <div class="col-12 form-group">
                                <label for="inputMessage">@lang('Message')</label>
                                <textarea name="message" id="inputMessage" rows="6" class="form-control form-control-lg">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <span for="inputAttachments text-white">@lang('Attachments')</span>
                            <input name="attachments[]" id="customFile" type="file" class="dropify custom-file-input"
                                data-height="180" accept=".jpg,.jpeg,.png,.pdf" />
                        </div>

                        <p class="text-muted m-2">
                            <i class="la la-info-circle"></i> @lang('Allowed File Extensions: .jpg, .jpeg, .png, .pdf')
                        </p>

                        <div class="row form-group justify-content-center">
                            <button class="btn btn-block btn-primary" type="submit"
                                id="recaptcha">@lang('Submit Ticket')</button>
                        </div>
                    </form>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
@endsection

@push('script')
    <!-- File uploads js -->
    <script src="{{ asset($activeTemplateTrue . 'dashboard/plugins/fileuploads/js/dropify.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/filupload.js') }}"></script>
@endpush

@push('style-lib')
    <!-- File Uploads css-->
    <link href="{{ asset($activeTemplateTrue . 'dashboard/plugins/fileuploads/css/dropify.css') }}" rel="stylesheet"
        type="text/css" />
@endpush
