@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @if (Auth::user()->verify == 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Verify Your Identity</h2>
                                <p>Upload your Front and Back Side of NIC or Passpoart. Also upload your Selfie holding your
                                    NIC or Passport.</p>
                            </div>
                        </div>
                        <form class="form-horizontal mt-4" method="post" action="" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('put') }}

                            <div class="form-row mt-3">
                                <div class="form-group col-md-6 offset-md-3">
                                    <label for="formFile" class="form-label">@lang('Upload Front ID')</label>
								    <input class="form-control" type="file" name="front" id="front">
                                </div>
                            </div>

                            <div class="form-row mt-3">
                                <div class="form-group col-md-6 offset-md-3">
                                    <label for="formFile" class="form-label">@lang('Upload Back ID')</label>
								    <input class="form-control" type="file" name="back" id="back">
                                </div>
                            </div>

                            <div class="form-row mt-3">
                                <div class="form-group col-md-6 offset-md-3">
                                    <label for="formFile" class="form-label">@lang('Upload Selfie With ID')</label>
								    <input class="form-control" type="file" name="selfie" id="selfie">
                                </div>
                            </div>

                            {{-- <div class="form-row mt-3">
                                <div class="form-group col-md-6 offset-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="btc_wallet" autocomplete="off"
                                            placeholder="@lang('Enter your BTC Wallet')*" required>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="form-row mt-3">
                                <div class="form-group col-md-6 offset-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="trc20_wallet"
                                            placeholder="@lang('Enter your Tether TRC20 Wallet')*" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions mt-4">
                                <div class="row">
                                    <div class="col-md-4 offset-md-4 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Submit
                                            Verification</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    @elseif(Auth::user()->verify == 1)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Verification Status</h2>
                                <h1 class="mt-4 text-warning mb-5">Pending</h1>
                            </div>
                        </div>
                    @elseif(Auth::user()->verify == 2)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Verification Status</h2>
                                <h1 class="mt-4 text-success mb-5">Identity Verified</h1>
                            </div>
                        </div>
                    @endif
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
