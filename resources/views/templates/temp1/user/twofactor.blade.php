@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <!--Row-->
    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h3 class="card-title">@lang('Two Factor Authenticator')</h3>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="mb-2">@lang('Use Google Authenticator to Scan the QR code  or use the code')</h3>
                    <p class="font-20">
                        {{ __('Google Authenticator is a multi factor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') }}
                    </p>
                    <a class="btn btn-primary btn-block"
                        href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                        target="_blank">@lang('DOWNLOAD APP')</a>

                    <div class="faq-contact mt-4">

                        @if (Auth::user()->ts == '1')
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="panel-title text-center">@lang('Two Factor Authenticator')</h4>
                                </div>
                                <div class="card-body min-height-310 text-center">
                                    <button type="button" class="btn btn-block btn-lg bttn btn-fill btn-danger show-disable-2fa-modal">
                                        @lang('Disable Two Factor Authenticator')</button>
                                </div>
                            </div>
                        @else
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="panel-title text-center">@lang('Two Factor Authenticator')</h4>
                                </div>
                                <div class="card-body text-center">
                                    <div class="input-group mb-3">
                                        <input type="text" name="key" value="{{ $secret }}"
                                            class="form-control" id="code" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="copybtnpp">@lang('Copy')</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <img src="{{ $qrCodeUrl }}">
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-block btn-primary show-enable-2fa-modal">
                                        @lang('Enable Two Factor Authenticator')</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End row-->

    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content blue-bg">
                <div class="modal-header">
                    <h4 class="modal-title text-dark">@lang('Verify Your OTP')</h4>
                    <button type="button" class="close text-white hide-enable-2fa-modal" data-dismiss="modal">&times;</button>

                </div>
                <form action="{{ route('user.twofactor.enable') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark hide-enable-2fa-modal" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success pull-right">@lang('Submit')</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content -->
            <div class="modal-content blue-bg ">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your OTP to Disable')</h4>
                    <button type="button" class="close hide-disable-2fa-modal" data-dismiss="modal">&times;</button>

                </div>
                <form action="{{ route('user.twofactor.disable') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block pull-left">@lang('Verify')</button>
                        <button type="button" class="btn btn-dark hide-disable-2fa-modal" data-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection


@push('script')
    <script>
        'use strict';
        document.getElementById("copybtnpp")?.addEventListener('click', function () {
            document.getElementById('code').select();
            document.execCommand('copy');
            notify('success', 'Copied successfully');
        });

        $('.show-disable-2fa-modal').on('click', function() {
            $('#disableModal').modal('show');
        });

        $('.hide-disable-2fa-modal').on('click', function() {
            $('#disableModal').modal('hide');
        });

        $('.show-enable-2fa-modal').on('click', function() {
            $('#enableModal').modal('show');
        });

        $('.hide-enable-2fa-modal').on('click', function() {
            $('#enableModal').modal('hide');
        });
    </script>
@endpush


@push('style')
    <style>
        .min-height-310 {
            min-height: 310px !important;
        }
    </style>
@endpush
