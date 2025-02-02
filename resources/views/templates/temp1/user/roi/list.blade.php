@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')
    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body bg-info">
                    <div class="row">
                        <div class="form-group col-md-12 mb-4">
                            <label class="font-weight-bold">@lang('Packages')</label>
                            <select class="form-control wallet_id" name="wallet_id">
                                <option value="">Select your plan</option>
                                @foreach ($myPLans as $data)
                                    <option value="{{ $data->id }}">{{ trans($data->plan->name) }}</option>
                                @endforeach
                            </select>
                        </div>  
                        <div class="form-group col-md-5">
                            <label class="font-weight-bold"> @lang('Amount') :</label>
                            <input class="form-control" name="amount" value="" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label class="font-weight-bold"> @lang('Range') :</label>
                            <input class="form-control" name="range" value="" required>
                        </div>
                        <div class="form-group col-md-2 mt-4">
                            <button class="btn btn-block btn-dark excute_roi_range">@lang('Execute')</button>
                        </div>   
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('ROI')</th>
                                    <th scope="col">@lang('Percentage')</th>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
@endsection

@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush