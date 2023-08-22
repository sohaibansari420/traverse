@extends('admin.layouts.app')

@push('style')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two" id="dataTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('User')</th>
                                    <th scope="col">@lang('Plan')</th>
                                    <th scope="col">@lang('Type')</th>
                                    <th scope="col">@lang('TRX')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Compounding')</th>
                                    <th scope="col">@lang('Daily Limit')</th>
                                    <th scope="col">@lang('Limit Consumed')</th>
                                    <th scope="col">@lang('Daily Return')</th>
                                    <th scope="col">@lang('Daily Status')</th>
                                    <th scope="col">@lang('Point Status')</th>
                                    <th scope="col">@lang('Auto Renew')</th>
                                    <th scope="col">@lang('Auto Compounding')</th>
                                    <th scope="col">@lang('Plan Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>


    </div>

    {{-- edit modal --}}
    <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Purchased Plan')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.plan.purchased.update') }}">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control plan_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-12 is_roi">
                                <label class="font-weight-bold">@lang('Daily Status')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="is_roi" checked>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12 is_expired">
                                <label class="font-weight-bold">@lang('Plan Status')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Expired')"
                                    name="is_expired" checked>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-block btn--primary">@lang('Update')</button>
                    </div>
                </form>
                <form method="post" action="{{ route('admin.plan.purchased.delete') }}">
                    @csrf
                    <input class="form-control plan_id" type="hidden" name="id">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-block btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{ route('admin.report.plan.purchased', $type) }}" method="GET"
        class="form-inline float-sm-right bg--white mr-0 mr-xl-2 mr-lg-0">
        <div class="input-group has_append ">
            <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - "
                data-language="en" class="datepicker-here form-control bg-white text--black"
                data-position='bottom right' placeholder="@lang('Min Date - Max date')" autocomplete="off" readonly
                value="{{ @$dateSearch }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            if (!$('.datepicker-here').val()) {
                $('.datepicker-here').datepicker();
            }
        })(jQuery)
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [0, 'desc'],
                /*"search": {
                    "search": "amano"
                },*/
                "ajax": {
                    "url": "{{ route('admin.reports.data', $data_type) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        type: "{{ $type }}",
                        @if(@$start && @$end)
                        startDate: "{{ $start }}",
                        endDate: "{{ $end }}",
                        @endif
                    }
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "user_id"
                    },
                    {
                        "data": "plan_id"
                    },
                    {
                        "data": "type"
                    },
                    {
                        "data": "trx"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": "compounding"
                    },
                    {
                        "data": "roi_limit"
                    },
                    {
                        "data": "limit_consumed"
                    },
                    {
                        "data": "roi_return"
                    },
                    {
                        "data": "is_roi"
                    },
                    {
                        "data": "with_point"
                    },
                    {
                        "data": "auto_renew"
                    },
                    {
                        "data": "auto_compounding"
                    },
                    {
                        "data": "is_expired"
                    },
                    {
                        "data": "action"
                    }
                ]

            });

            $("#dataTable").on("click", ".edit", function(){
                var modal = $('#edit-modal');

                if ($(this).data('is_roi')) {
                    modal.find('.is_roi .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="is_roi"]').prop('checked', true);

                } else {
                    modal.find('.is_roi .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="is_roi"]').prop('checked', false);
                }

                if ($(this).data('is_expired')) {
                    modal.find('.is_expired .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="is_expired"]').prop('checked', false);
                } else {
                    modal.find('.is_expired .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="is_expired"]').prop('checked', true);
                }
                
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        });
    </script>
@endpush
