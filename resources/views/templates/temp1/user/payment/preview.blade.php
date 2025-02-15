@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-md-12">

                <div class="card card-deposit text-center">
                    <div class="card-body card-body-deposit">
                        <ul class="list-group text-center">
                            <li class="list-group-item ">
                                <img class="max-w-h-100px" src="{{ $data->gateway_currency()->methodImage() }}" />
                            </li>
                            <p class="list-group-item">
                                @lang('Amount'):
                                <strong>{{ getAmount($data->amount) }} </strong> {{ $general->cur_text }}
                            </p>
                            <p class="list-group-item">
                                @lang('Charge'):
                                <strong>{{ getAmount($data->charge) }}</strong> {{ $general->cur_text }}
                            </p>
                            <p class="list-group-item">
                                @lang('Payable'): <strong> {{ getAmount($data->amount + $data->charge) }}</strong>
                                {{ $general->cur_text }}
                            </p>
                            <p class="list-group-item">
                                @lang('Conversion Rate'): <strong>1 {{ $general->cur_text }} = {{ getAmount($data->rate) }}
                                    {{ $data->baseCurrency() }}</strong>
                            </p>
                            <p class="list-group-item">
                                @lang('In') {{ $data->baseCurrency() }}:
                                <strong>{{ getAmount($data->final_amo) }}</strong>
                            </p>
                            @if ($data->gateway->crypto == 1)
                                <p class="list-group-item">
                                    @lang('Conversion with')
                                    <b> {{ $data->method_currency }}</b> @lang('and final value will Show on next step')
                                </p>
                            @endif
                        </ul>

                        @if (1000 > $data->method_code)
                            <a href="{{ route('user.deposit.confirm') }}"
                                class="btn btn-primary btn-block py-3 font-weight-bold">@lang('Confirm Deposit')</a>
                        @else
                            <a href="{{ route('user.deposit.manual.confirm') }}"
                                class="btn btn-primary btn-block py-3 font-weight-bold">@lang('Confirm Deposit')</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
