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
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('TRX')</th>
                                    <th scope="col">@lang('Wallet')</th>
                                    <th scope="col">@lang('Commission')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Charge')</th>
                                    <th scope="col">@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $trx)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $transactions->firstItem() + $loop->index }}
                                        </td>
                                        <td data-label="@lang('Date')">{{ showDateTime($trx->created_at) }}</td>
                                        <td data-label="@lang('TRX')" class="font-weight-bold">{{ $trx->trx }}
                                        </td>
                                        <td data-label="@lang('Wallet')" class="font-weight-bold">
                                            {{ @$trx->wallet->name }}
                                        </td>
                                        <td data-label="@lang('Commission')" class="font-weight-bold">
                                            {{ @$trx->commission->name }}</td>
                                        <td data-label="@lang('Amount')" class="budget">
                                            <strong
                                                @if ($trx->trx_type == '+') class="text-success"
                                                @elseif ($trx->trx_type == '-') 
                                                    class="text-danger" @endif>
                                                @if ($trx->trx_type == '+')
                                                    +
                                                @elseif ($trx->trx_type == '-')
                                                    -
                                                @endif
                                                {{ getAmount($trx->amount) }}
                                                {{ $general->cur_text }}
                                            </strong>
                                        </td>
                                        <td data-label="@lang('Charge')" class="budget">{{ $general->cur_sym }}
                                            {{ getAmount($trx->charge) }} </td>
                                        <td data-label="@lang('Detail')">{{ __($trx->details) }}</td>
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