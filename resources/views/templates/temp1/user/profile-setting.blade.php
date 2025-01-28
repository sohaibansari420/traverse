@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-3 wow fadeInUp" data-wow-delay="1.5s">
            <div class="card ">
                <div class="card-body">
                    <div class="inner-all">
                        <ul class="list-unstyled">
                            <li class="text-center border-bottom-0">
                                <img data-no-retina="" style="width: 100%" id="output" class="img-circle img-responsive img-bordered-primary"
                                    src="{{ getImage('assets/images/user/profile/' . auth()->user()->image, '350x300') }}"
                                    alt="User Profile">
                            </li>
                            <li class="text-center">
                                <h4 class="text-capitalize mt-3 mb-0"> {{ auth()->user()->fullname }}</h4>
                                <p class="text-muted text-capitalize">{{ auth()->user()->username }}</p>
                                <p class="text-muted text-capitalize">
                                    <span>@lang('Joining Date'): </span>
                                    {{ date('d M, Y', strtotime(auth()->user()->created_at)) }}
                                </p>
                            </li>
                            <li>
                                <a href="{{ route('user.logout') }}" class="btn btn-primary text-center btn-block">Sign
                                    Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ auth()->user()->fullname }} @lang('Information')</h3>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('First Name') <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control form-control-lg" type="text" name="firstname"
                                                value="{{ auth()->user()->firstname }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label  font-weight-bold">@lang('Last Name') <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control form-control-lg" type="text" name="lastname"
                                                value="{{ auth()->user()->lastname }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Email')<span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control form-control-lg" type="email"
                                                value="{{ auth()->user()->email }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label  font-weight-bold">@lang('Mobile Number')<span
                                                    class="text-danger">*</span></label>
                                            <input name="mobile" class="form-control form-control-lg" type="text"
                                                value="{{ auth()->user()->mobile }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label  font-weight-bold">@lang('Avatar')</label>
                                            <input class="form-control form-control-lg" type="file" accept="image/*"
                                                onchange="loadFile(event)" name="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('BTC Wallet')<span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control form-control-lg" type="text"
                                                placeholder="BTC Wallet" value="{{ auth()->user()->btc_wallet }}" readonly>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label  font-weight-bold">@lang('Trc20 Wallet')<span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control form-control-lg" type="text"
                                                value="{{ auth()->user()->trc20_wallet }}" placeholder="Trc20 Wallet"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 hide">
                                    <div class="col-md-12 d-none">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                            <input class="form-control form-control-lg" type="text" name="address"
                                                value="{{ auth()->user()->address->address }}">
                                            <small class="form-text text-muted"><i
                                                    class="las la-info-circle"></i>@lang('House number, street address')
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 d-none">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold">@lang('City')</label>
                                            <input class="form-control form-control-lg" type="text" name="city"
                                                value="{{ auth()->user()->address->city }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 d-none">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('State')</label>
                                            <input class="form-control form-control-lg" type="text" name="state"
                                                value="{{ auth()->user()->address->state }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 d-none">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Zip/Postal')</label>
                                            <input class="form-control form-control-lg" type="text" name="zip"
                                                value="{{ auth()->user()->address->zip }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Country')</label>
                                            <select name="country" class="form-control form-control-lg">
                                                @include('partials.country') </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-primary btn-block btn-lg">@lang('Save Changes')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $("select[name=country]").val("{{ auth()->user()->address->country }}");
        })(jQuery)

        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src)
            }
        };
    </script>
@endpush
