@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="swiper mySwiper-counter position-relative overflow-hidden">
        <div class="swiper-wrapper ">
            <!--swiper-slide-->
            @foreach ($wallets as $wallet)
                @if ($wallet->wallet->display)
                    <div class="swiper-slide">
                        <div class="card counter">
                            <div class="card-body d-flex align-items-center">
                                <div class="card-box-icon">
                                    {!! $wallet->wallet->icon !!}
                                </div>
                                <div  class="chart-num">
                                    <h2 class="font-w600 mb-0">{{ getAmount($wallet->balance) }} <small>{{ $wallet->wallet->currency }}</small></h2>
                                    <p>
                                        {{ $wallet->wallet->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('user.report.wallet') }}?walletID={{ $wallet->wallet->id }}"
                                        class="btn btn-primary btn-sm">Logs</a>
                                    @if ($wallet->wallet->withdraw)
                                        <a href="{{ route('user.withdraw') }}?walletID={{ $wallet->wallet->id }}"
                                            class="btn btn-primary btn-sm">
                                            Withdraw</a>
                                    @endif
                                    @if ($wallet->wallet->deposit)
                                        <a href="{{ route('user.deposit') }}"
                                            class="btn btn-primary btn-sm">Deposit</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>	
                @endif
            @endforeach
        </div>		
    </div>
@stop