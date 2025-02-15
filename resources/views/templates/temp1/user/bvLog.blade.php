@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }} Details</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Paid left')</th>
                                    <th scope="col">@lang('Paid right')</th>
                                    <th scope="col">@lang('Free left')</th>
                                    <th scope="col">@lang('Free right')</th>
                                    <th scope="col">@lang('Bv left')</th>
                                    <th scope="col">@lang('Bv right')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="@lang('Paid left')">{{ $summery->paid_left }}</td>
                                    <td data-label="@lang('Paid right')">{{ $summery->paid_right }}</td>
                                    <td data-label="@lang('Free left')">{{ $summery->free_left }}</td>
                                    <td data-label="@lang('Free right')">{{ $summery->free_right }}</td>
                                    <td data-label="@lang('Bv left')">{{ getAmount($summery->bv_left) }}</td>
                                    <td data-label="@lang('Bv right')">{{ getAmount($summery->bv_right) }}</td>
                                </tr>
                            </tbody>
                        </table>
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
                    <div class="card-title text-white">{{ $page_title }} Logs</div>
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
                                    <th scope="col">@lang('Sl')</th>
                                    <th scope="col">@lang('BV')</th>
                                    <th scope="col">@lang('Position')</th>
                                    <th scope="col">@lang('Detail')</th>
                                    <th scope="col">@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $key => $data)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('BV')" class="budget">
                                            <strong
                                                @if ($data->trx_type == '+') class="text-success" @else class="text-danger" @endif>
                                                {{ $data->trx_type == '+' ? '+' : '-' }}
                                                {{ getAmount($data->amount) }}</strong>
                                        </td>

                                        <td data-label="@lang('Position')">
                                            @if ($data->position == 1)
                                                <span class="badge badge--success">@lang('Left')</span>
                                            @else
                                                <span class="badge badge--primary">@lang('Right')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Detail')">{{ $data->details }}</td>
                                        <td data-label="@lang('Date')">
                                            {{ $data->created_at != '' ? date('d/m/y  g:i A', strtotime($data->created_at)) : __('Not Assign') }}
                                        </td>
                                    </tr>
                                @endforeach

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
