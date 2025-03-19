@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.deposit.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <input type="hidden" name="old_btc" value="{{ $config->btc_wallet ?? '' }}">
                                    <label class="form-control-label font-weight-bold"> @lang('BTC Wallet') </label>
                                    <input class="form-control form-control-lg" type="text" name="btc_wallet"
                                        value="{{ $config->btc_wallet ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush
@push('style')
 
@endpush

@push('script')
@endpush
