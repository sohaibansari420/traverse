@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Deposit</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!--Row-->
                    <div class="row mt-4 justify-content-center">
                        @foreach ($gatewayCurrency as $data)
                            <div class="col-lg-4 col-sm-12 p-l-0 p-r-0">
                                <div class="card text-center">

                                    <button class="btn btn-primary btn-block btn-lg">{{ __($data->name) }}</button>

                                    <div class="widget-line mt-4">
                                        <h4>Deposit via {{ __($data->name) }}</h4>
                                    </div>
                                    <div class="mx-auto chart-circle chart-circle-md" data-value="0.55" data-thickness="20"
                                        data-color="#38a01e">
                                        <div class="chart-circle-value fs">
                                            <img src="{{ $data->methodImage() }}" class="box-img-top depo"
                                                alt="{{ __($data->name) }}" width="200px" height="200px">
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mt-5 mb-5">

                                        <div class="col-11">
                                            <a href="javascript:void(0)" type="button" data-id="{{ $data->id }}"
                                                data-resource="{{ $data }}"
                                                data-min_amount="{{ getAmount($data->min_amount) }}"
                                                data-max_amount="{{ getAmount($data->max_amount) }}"
                                                data-base_symbol="{{ $data->baseSymbol() }}"
                                                data-fix_charge="{{ getAmount($data->fixed_charge) }}"
                                                data-percent_charge="{{ getAmount($data->percent_charge) }}"
                                                class=" btn btn-primary btn-block deposit" data-bs-toggle="modal"
                                                data-bs-target="#depositModal">
                                                @lang('Deposit Now')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!--End row-->

                    <!-- Modal -->
                    <div class="modal" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModal"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="depositModal">@lang('Confirm Deposit')?
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <form action="{{ route('user.deposit.insert') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="currency" class="edit-currency" value="">
                                            <input type="hidden" name="method_code" class="edit-method-code"
                                                value="">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Enter Amount'):</label>
                                            <div class="input-group">
                                                <input id="amount" type="text" class="form-control form-control-lg"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    name="amount" placeholder="0.00" required value="{{ old('amount') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer modal-footer-uniform">
                                        <button type="submit"
                                            class="btn btn-block btn-lg btn-primary">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- section-wrapper -->
        </div>
    </div>

@stop

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.deposit').on('click', function() {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var baseSymbol = "{{ $general->cur_text }}";
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Deposit Limit:') ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge =
                    `@lang('Charge:') ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Payment By ') ${result.name}`);
                $('.currency-addon').text(baseSymbol);
                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.method_code);
            });
        })(jQuery);
    </script>
@endpush
