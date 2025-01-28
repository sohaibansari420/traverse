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
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Date/Time')</th>
                                    <th scope="col">@lang('IP')</th>
                                    <th scope="col">@lang('Location')</th>
                                    <th scope="col">@lang('Browser')</th>
                                    <th scope="col">@lang('OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($login_logs as $key => $log)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Date')">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }} ({{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }})</td>
                                        <td data-label="@lang('IP')">{{ $log->user_ip }}</td>
                                        <td data-label="@lang('Location')">{{ $log->location }}</td>
                                        <td data-label="@lang('Browser')">{{ $log->browser }}</td>
                                        <td data-label="@lang('OS')">{{ $log->os }}</td>
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