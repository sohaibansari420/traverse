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
                                    <th scope="col">@lang('Transaction ID')</th>
                                    <th scope="col">@lang('Gateway')</th>
                                    <th scope="col">@lang('Wallet')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Charge')</th>
                                    <th scope="col">@lang('After Charge')</th>
                                    <th scope="col">@lang('Rate')</th>
                                    <th scope="col">@lang('Receivable')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Time')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdraws as $k=>$data)
                                    <tr>
                                        <td data-label="#@lang('Trx')">{{ $data->trx }}</td>
                                        <td data-label="@lang('Gateway')">{{ $data->method->name }}</td>
                                        <td data-label="@lang('Wallet')">{{ $data->wallet->name }}</td>
                                        <td data-label="@lang('Amount')">
                                            <strong>{{ getAmount($data->amount) }} {{ $general->cur_text }}</strong>
                                        </td>
                                        <td data-label="@lang('Charge')" class="text--danger">
                                            {{ getAmount($data->charge) }} {{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('After Charge')">
                                            {{ getAmount($data->after_charge) }} {{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('Rate')">
                                            {{ getAmount($data->rate) }} {{ $data->currency }}
                                        </td>
                                        <td data-label="@lang('Receivable')" class="text--success">
                                            <strong>{{ getAmount($data->final_amount) }} {{ $data->currency }}</strong>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if ($data->status == 2)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($data->status == 1)
                                                <span class="badge badge--success">@lang('Completed')</span>
                                                <button class="btn-primary btn-rounded  badge approveBtn"
                                                    data-admin_feedback="{{ $data->admin_feedback }}"><i
                                                        class="fa fa-info"></i></button>
                                            @elseif($data->status == 3)
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                                <button class="btn-primary btn-rounded badge approveBtn"
                                                    data-admin_feedback="{{ $data->admin_feedback }}"><i
                                                        class="fa fa-info"></i></button>
                                            @endif

                                        </td>
                                        <td data-label="@lang('Time')">
                                            <i class="las la-calendar"></i> {{ showDateTime($data->created_at) }}
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

    <!-- Modal -->
    <div class="modal" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModal">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" >
                    </button>
                </div>
                <div class="modal-body">
                    <div class="withdraw-detail"></div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                        aria-label="Close">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.approveBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');

                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush

@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
