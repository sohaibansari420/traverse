@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Sl')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Plan')</th>
                                    <th scope="col">@lang('Level')</th>
                                    <th scope="col">@lang('Percent')</th>
                                    <th scope="col">@lang('Limit')</th>
                                    <th scope="col">@lang('Capping')</th>
                                    <th scope="col">@lang('Days')</th>
                                    <th scope="col">@lang('Direct')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissionDetail as $key => $commission)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Name')">{{ __($commission->commission->name) }}</td>
                                        <td data-label="@lang('Plan')">{{ __(@$commission->plan->name) }}</td>
                                        <td data-label="@lang('Level')">{{ __(@$commission->level) }}</td>
                                        <td data-label="@lang('Percent')">{{ __(@getAmount($commission->percent)) }}%</td>
                                        <td data-label="@lang('Limit')">
                                            {{ __(@getAmount($commission->commission_limit)) }}X</td>
                                        <td data-label="@lang('Capping')">{{ __(@getAmount($commission->capping)) }}%</td>
                                        <td data-label="@lang('Days')">{{ __(@getAmount($commission->days)) }}</td>
                                        <td data-label="@lang('Direct')">{{ __(@$commission->direct) }}</td>


                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $commission->id }}"
                                                data-commission_id="{{ $commission->commission_id }}"
                                                data-plan_id="{{ $commission->plan_id }}"
                                                data-level="{{ $commission->level }}"
                                                data-direct="{{ $commission->direct }}"
                                                data-percent="{{ getAmount($commission->percent) }}"
                                                data-limit="{{ getAmount($commission->commission_limit) }}"
                                                data-capping="{{ getAmount($commission->capping) }}"
                                                data-days="{{ getAmount($commission->days) }}" data-original-title="Edit">
                                                <i class="la la-pencil"></i>
                                            </button>
                                            <a href="javascript:void(0)" class="icon-btn btn--danger deleteBtn  ml-1"
                                                data-toggle="tooltip" data-original-title="@lang('Delete')"
                                                data-id="{{ $commission->id }}">
                                                <i class="la la-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $commissionDetail->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div id="edit-commission-detail" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Commission Detail')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.commission.detail.update') }}">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control commissionDetail_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Commission')</label>
                                <select class="form-control commission_id" name="commission_id">
                                    @foreach ($commissions as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Plan')</label>
                                <select class="form-control plan_id" name="plan_id">
                                    <option value="">No Plan Selected</option>
                                    @foreach ($plans as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Level') :</label>
                                <input class="form-control level" name="level">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Direct') :</label>
                                <input class="form-control direct" name="direct">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Percent') :</label>
                                <input class="form-control percent" name="percent" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Limit') :</label>
                                <input class="form-control limit" name="commission_limit">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Capping') :</label>
                                <input class="form-control capping" name="capping">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Days') :</label>
                                <input class="form-control days" name="days">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-block btn--primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-commission-detail" class="modal  fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Commission Detail')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.commission.detail.store') }}">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control commissionDetail_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Commission')</label>
                                <select class="form-control" name="commission_id">
                                    @foreach ($commissions as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Plan')</label>
                                <select class="form-control" name="plan_id">
                                    <option value="">No Plan Selected</option>
                                    @foreach ($plans as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Level') :</label>
                                <input class="form-control" name="level">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Direct') :</label>
                                <input class="form-control" name="direct">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Percent') :</label>
                                <input class="form-control" name="percent">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Limit') :</label>
                                <input class="form-control" name="commission_limit">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Capping') :</label>
                                <input class="form-control" name="capping">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Days') :</label>
                                <input class="form-control" name="days">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-block btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Delete MODAL --}}
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Delete Record Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.commission.detail.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete the Record?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--success add-commission-detail"><i
            class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-commission-detail');

                modal.find('.commission_id option[value=' + $(this).data('commission_id') + ']').attr(
                    'selected',
                    'selected');



                if ($(this).data('plan_id')) {
                    modal.find('.plan_id option[value=' + $(this).data('plan_id') + ']').attr(
                        'selected',
                        'selected');

                }

                modal.find('.level').val($(this).data('level'));
                modal.find('.percent').val($(this).data('percent'));
                modal.find('.limit').val($(this).data('limit'));
                modal.find('.capping').val($(this).data('capping'));
                modal.find('.days').val($(this).data('days'));
                modal.find('.direct').val($(this).data('direct'));

                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.add-commission-detail').on('click', function() {
                var modal = $('#add-commission-detail');
                modal.modal('show');
            });

            $('.deleteBtn').on('click', function() {
                var modal = $('#deleteModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
