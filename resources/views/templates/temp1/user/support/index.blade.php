@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="{{ route('ticket.open') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>
                            @lang('Generate Ticket')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Subject')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Last Reply')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supports as $key => $support)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Subject')"> <a
                                                href="{{ route('ticket.view', $support->ticket) }}"
                                                class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}]
                                                {{ $support->subject }} </a></td>
                                        <td data-label="@lang('Status')">
                                            @if ($support->status == 0)
                                                <span class="badge badge-success">@lang('Open')</span>
                                            @elseif($support->status == 1)
                                                <span class="badge badge-primary ">@lang('Answered')</span>
                                            @elseif($support->status == 2)
                                                <span class="badge badge-warning">@lang('Reply')</span>
                                            @elseif($support->status == 3)
                                                <span class="badge badge-danger">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Last Reply')">
                                            {{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn-primary"
                                                data-toggle="tooltip" title=""
                                                data-original-title="@lang('Details')">
                                                <i class="fa fa-desktop text-shadow"></i>
                                            </a>

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
