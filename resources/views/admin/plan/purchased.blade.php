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
                            <tbody>
                                @forelse($plans as $key => $plan)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('User')">{{ __($plan->user->username) }}</td>
                                        <td data-label="@lang('Plan')">{{ __($plan->plan->name) }}</td>
                                        <td data-label="@lang('Type')">{{ __($plan->type) }}</td>
                                        <td data-label="@lang('TRX')">{{ __($plan->trx) }}</td>
                                        <td data-label="@lang('Amount')">{{ getAmount($plan->amount) }}
                                            {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Compunding')">{{ getAmount($plan->compunding) }}
                                            {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Daily Limit')">{{ $plan->roi_limit }}</td>
                                        <td data-label="@lang('Limit Consumed')">{{ $plan->limit_consumed }}</td>
                                        <td data-label="@lang('Daily Return')">{{ $plan->roi_return }}</td>

                                        <td data-label="@lang('Daily Status')">
                                            @if ($plan->is_roi == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Point Status')">
                                            @if ($plan->with_point == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('With Points')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Without Points')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Auto Renew')">
                                            @if ($plan->auto_renew == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        
                                        <td data-label="@lang('Auto Compouding')">
                                            @if ($plan->auto_compounding == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Plan Status')">
                                            @if ($plan->is_expired == 0)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Expired')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $plan->id }}" data-is_roi="{{ $plan->is_roi }}"
                                                data-is_expired="{{ $plan->is_expired }}" data-original-title="Edit">
                                                <i class="la la-pencil"></i>
                                            </button>
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
                    {{ $plans->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div id="edit-plan" class="modal fade" tabindex="-1" role="dialog">
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
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-plan');

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

        })(jQuery);
    </script>
@endpush
