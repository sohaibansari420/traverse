@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('TRX')</th>
                                    <th scope="col">@lang('Username')</th>
                                    <th scope="col">@lang('Wallet')</th>
                                    <th scope="col">@lang('Commission')</th>
                                    <th scope="col">@lang('Plan TRX')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Charge')</th>
                                    <th scope="col">@lang('Post Balance')</th>
                                    <th scope="col">@lang('Detail')</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr>
                                        <td data-label="@lang('Date')">{{ showDateTime($trx->created_at) }}</td>
                                        <td data-label="@lang('TRX')" class="font-weight-bold">{{ $trx->trx }}
                                        </td>
                                        <td data-label="@lang('Username')"><a
                                                href="{{ route('admin.users.detail', $trx->user_id) }}">{{ @$trx->user->username }}</a>
                                        </td>
                                        <td data-label="@lang('Wallet')" class="font-weight-bold">
                                            {{ @$trx->wallet->name }}
                                        </td>
                                        <td data-label="@lang('Commission')" class="font-weight-bold">
                                            {{ @$trx->commission->name }}
                                        </td>
                                        <td data-label="@lang('Plan TRX')" class="font-weight-bold">{{ $trx->plan_trx }}
                                        </td>
                                        <td data-label="@lang('Amount')" class="budget">
                                            <strong
                                                @if ($trx->trx_type == '+') class="text-success" @else class="text-danger" @endif>
                                                {{ $trx->trx_type == '+' ? '+' : '-' }} {{ getAmount($trx->amount) }}
                                                {{ $general->cur_text }}</strong>
                                        </td>
                                        <td data-label="@lang('Charge')" class="budget">{{ $general->cur_sym }}
                                            {{ getAmount($trx->charge) }} </td>
                                        <td data-label="@lang('Post Balance')">{{ $trx->post_balance + 0 }}
                                            {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Detail')">{{ __($trx->details) }}</td>
                                        <td>
                                            @if($trx->remark=="VIP_Unilevel_Bonus")
                                                <button type="button" onclick="deleteUnilevel({{ $trx->id }})" class="btn btn-danger">
                                                    Delete
                                                </button>
                                                <button type="button" onclick="EditUnilevel({{ $trx->id }})" class="btn btn-primary">
                                                    Edit
                                                </button>
                                            @endif
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
                    {{ $transactions->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
    <div class="modal" id="editUnilevel" tabindex="-1" role="dialog"
        aria-labelledby="editUnilevel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_title">
                        @lang('Confirm Edit' )?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                     this is modal
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    @if (request()->routeIs('admin.users.transactions'))
        <form action="" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('TRX / Username')"
                    value="{{ $search ?? '' }}">
                <input type="hidden" name="type" value="{{ @$type }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @else
        <form action="{{ route('admin.report.transaction.search') }}" method="GET"
            class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('TRX / Username')"
                    value="{{ $search ?? '' }}">
                <input type="hidden" name="type" value="{{ @$type }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <form action="{{ route('admin.report.transaction.dateSearch') }}" method="GET"
            class="form-inline float-sm-right bg--white mr-0 mr-xl-2 mr-lg-0">
            <div class="input-group has_append ">
                <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - "
                    data-language="en" class="datepicker-here form-control bg-white text--black"
                    data-position='bottom right' placeholder="@lang('Min Date - Max date')" autocomplete="off" readonly
                    value="{{ @$dateSearch }}">
                <input type="hidden" name="type" value="{{ @$type }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @endif
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            if (!$('.datepicker-here').val()) {
                $('.datepicker-here').datepicker();
            }
        })(jQuery)

        function deleteUnilevel(id) {

            $.ajax({
                url: "{{ route('admin.report.delete.unilevel', '') }}" + '/' + id,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id : id,
                },
                success: function(data) {
                    console.log(data);
                    window.location.reload();
                }
            });
        }
        function EditUnilevel(id){
            $('#editUnilevel').modal('show');
        }
    </script>
@endpush
