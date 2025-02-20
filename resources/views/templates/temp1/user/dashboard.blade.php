@extends($activeTemplate . 'user.layouts.app')

@section('panel')
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
                                    @if($wallet->wallet->id == 3)
                                        @if(\Carbon\Carbon::now()->dayOfWeek == \Carbon\Carbon::SATURDAY)
                                            <a href="{{ route('user.withdraw') }}?walletID={{ $wallet->wallet->id }}"
                                                class="btn btn-primary btn-sm">
                                                Withdraw</a>
                                        @endif
                                    @else
                                        <a href="{{ route('user.withdraw') }}?walletID={{ $wallet->wallet->id }}"
                                            class="btn btn-primary btn-sm">
                                            Withdraw</a>
                                    @endif
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
<div class="row">
    <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
        
        <div class="card overflow-hidden">
            <div class="card-header" style="display:block;">
                <h1 class="text-center">Balances & Bonuses!!!</h1>
            </div>
            {{-- <div class="card-body pb-0">
                <div class="row">
                    <div class="col">
                        <h5 class="text-white"><a href="{{ route('user.report.deposit') }}">Total Deposit</a></h5>
                        <span class="text-white">{{ $general->cur_sym }}{{ getAmount($totalDeposit) }}</span>
                    </div>
                    <div class="col text-end">
                        <h5 class="text-white"><a href="{{ route('user.report.withdraw') }}">Total Withdrawal</a></h5>
                        <span class="text-white">{{ $general->cur_sym }}{{ getAmount($totalWithdraw) }}</span>
                    </div>
                </div>
                
            </div> --}}
            <div class="card-body pb-0 mb-2">
                <div class="row text-center">
                    {{-- <div class="col-12">
                        <p class="mb-0 fs-15">The Traverse Bot Speed Bonus rewards new members of The Traverse Bot ecosystem who sponsor 5 members within the first 15 days of joining. These members must have the same or greater value than your joining package.</p>
                    </div> --}}
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h5 class="text-white"><a href="{{ route('user.report.deposit') }}">Total Deposit</a></h5>
                            <span class="text-white">{{ $general->cur_sym }}{{ getAmount($totalDeposit) }}</span>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h5 class="text-white"><a href="{{ route('user.report.withdraw') }}">Total Withdrawal</a></h5>
                            <span class="text-white">{{ $general->cur_sym }}{{ getAmount($totalWithdraw) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="chart-wrapper">
                <div id="chart_widget_5"></div>
                <div class="px-4"><span class="peity-line" data-width="100%">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span></div>
            </div> --}}
            <div class="card-footer pt-0 pb-0 text-center">
                <div class="row mt-4">
                {{-- @foreach ($commissions as $commission)
                    @if($commission->id != 7)
                        <div class="bonus-card m-3 col-md-6">
                            <div class="bonus-title">
                                <div class="bonus-price">
                                    <a href="{{ route('user.report.commission') }}?commissionID={{ $commission->id }}"><span>{{ $commission->name }}</span></a>
                                </div>
                                <h3 class="mb-1">
                                    @if($commission->id == 1)
                                        {{ $general->cur_sym }}{{ getAmount(App\Models\Transaction::where('commission_id', $commission->id)->where('user_id', Auth::id())->sum('amount')) }}
                                    @else
                                        {{ $general->cur_sym }}{{ getAmount(App\Models\Transaction::where('commission_id', $commission->id)->where('wallet_id', '!=', 6)->where('user_id', Auth::id())->sum('amount')) }}
                                    @endif
                                </h3>
                            </div>
                        </div>
                    @endif
                @endforeach --}}
                {{-- <div class="col-3 pt-3 pb-3 border-end">
                    <h3 class="mb-1">
                        @php
                            $founder=App\Models\Founder::where('status', 'paid')->where('user_id', Auth::id());
                        @endphp
                        {{ $general->cur_sym }}{{ getAmount($founder->sum('amount')) }}
                    </h3>
                    
                </div> --}}
                {{-- <div class="bonus-card m-3 col-md-6">
                    <div class="bonus-title">
                        <div class="bonus-price">
                            <a href=""><span>Founder Bonus</span></a>
                        </div>
                        <h3 class="mb-1">
                            @php
                                $founder=App\Models\Founder::where('status', 'paid')->where('user_id', Auth::id());
                            @endphp
                            {{ $general->cur_sym }}{{ getAmount($founder->sum('amount')) }}
                        </h3>
                    </div>
                </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div clsss=" row">
    <div class="col-xl-12 row wow fadeInUp" data-wow-delay="1.2s">
        @foreach ($commissions as $commission)
            @if($commission->id != 7)
                <div class="col-md-4">
                    <div class="card counter">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-box-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M35.0001 21.4C34.9363 21.0057 34.7328 20.6474 34.4269 20.3904C34.121 20.1334 33.733 19.9949 33.3335 20H26.6668C26.91 20.0032 27.151 19.953 27.3728 19.8531C27.5946 19.7532 27.7918 19.606 27.9507 19.4217C28.1095 19.2375 28.2261 19.0207 28.2922 18.7866C28.3583 18.5525 28.3724 18.3068 28.3335 18.0667L26.6668 8.0667C26.6029 7.67233 26.3995 7.31402 26.0936 7.05706C25.7877 6.80009 25.3996 6.66156 25.0001 6.6667H15.0001C14.6007 6.66156 14.2126 6.80009 13.9067 7.05706C13.6008 7.31402 13.3974 7.67233 13.3335 8.0667L11.6668 18.0667C11.6287 18.3061 11.6433 18.5509 11.7095 18.784C11.7757 19.0172 11.8919 19.2331 12.0501 19.4167C12.2084 19.6018 12.4054 19.7499 12.6271 19.8507C12.8488 19.9515 13.0899 20.0025 13.3335 20H6.66681C6.26733 19.9949 5.87929 20.1334 5.57337 20.3904C5.26746 20.6474 5.06403 21.0057 5.00014 21.4L3.33348 31.4C3.29537 31.6394 3.30992 31.8842 3.37613 32.1173C3.44233 32.3505 3.55859 32.5664 3.71681 32.75C3.87511 32.9351 4.07204 33.0833 4.29377 33.1841C4.51549 33.2848 4.7566 33.3358 5.00014 33.3334H18.3335C18.5742 33.3334 18.8121 33.2812 19.0308 33.1806C19.2494 33.0799 19.4437 32.933 19.6001 32.75C19.7584 32.5664 19.8746 32.3505 19.9408 32.1173C20.007 31.8842 20.0216 31.6394 19.9835 31.4L18.3168 21.4C18.2533 21.0085 18.0523 20.6525 17.7499 20.3958C17.4475 20.1392 17.0634 19.9989 16.6668 20H23.3335C22.934 19.9949 22.5459 20.1334 22.24 20.3904C21.9341 20.6474 21.7307 21.0057 21.6668 21.4L20.0001 31.4C19.962 31.6394 19.9766 31.8842 20.0428 32.1173C20.109 32.3505 20.2253 32.5664 20.3835 32.75C20.5418 32.9351 20.7387 33.0833 20.9604 33.1841C21.1822 33.2848 21.4233 33.3358 21.6668 33.3334H26.6668C27.1088 33.3334 27.5328 33.1578 27.8453 32.8452C28.1579 32.5327 28.3335 32.1087 28.3335 31.6667C28.3335 31.2247 28.1579 30.8008 27.8453 30.4882C27.5328 30.1756 27.1088 30 26.6668 30H23.6335L24.7501 23.3334H31.9168L33.0335 30H31.6668C31.2248 30 30.8009 30.1756 30.4883 30.4882C30.1757 30.8008 30.0001 31.2247 30.0001 31.6667C30.0001 32.1087 30.1757 32.5327 30.4883 32.8452C30.8009 33.1578 31.2248 33.3334 31.6668 33.3334H35.0001C35.2434 33.3365 35.4843 33.2864 35.7061 33.1865C35.9279 33.0866 36.1252 32.9393 36.284 32.7551C36.4428 32.5708 36.5594 32.3541 36.6255 32.12C36.6917 31.8859 36.7057 31.6402 36.6668 31.4L35.0001 21.4ZM6.96681 30L8.08348 23.3334H15.2501L16.3668 30H6.96681ZM15.3001 16.6667L16.4168 10H23.5835L24.7001 16.6667H15.3001Z" fill="#EB62D0"/>
                                </svg>	
                            </div>
                            <div  class="chart-num">
                                <h2 class="font-w600 mb-0">@if($commission->id == 1)
                                        {{ $general->cur_sym }}{{ getAmount(App\Models\Transaction::where('commission_id', $commission->id)->where('user_id', Auth::id())->sum('amount')) }}
                                    @else
                                        {{ $general->cur_sym }}{{ getAmount(App\Models\Transaction::where('commission_id', $commission->id)->where('wallet_id', '!=', 6)->where('user_id', Auth::id())->sum('amount')) }}
                                    @endif
                                </h2>
                                <p>
                                    <a href="{{ route('user.report.commission') }}?commissionID={{ $commission->id }}"><span>{{ $commission->name }}</span></a>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('user.report.commission') }}?commissionID={{ $commission->id }}"
                                    class="btn btn-primary btn-sm">Logs</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        {{-- <div class="card counter col-md-3">
            <div class="card-body d-flex align-items-center">
                <div class="card-box-icon">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M35.0001 21.4C34.9363 21.0057 34.7328 20.6474 34.4269 20.3904C34.121 20.1334 33.733 19.9949 33.3335 20H26.6668C26.91 20.0032 27.151 19.953 27.3728 19.8531C27.5946 19.7532 27.7918 19.606 27.9507 19.4217C28.1095 19.2375 28.2261 19.0207 28.2922 18.7866C28.3583 18.5525 28.3724 18.3068 28.3335 18.0667L26.6668 8.0667C26.6029 7.67233 26.3995 7.31402 26.0936 7.05706C25.7877 6.80009 25.3996 6.66156 25.0001 6.6667H15.0001C14.6007 6.66156 14.2126 6.80009 13.9067 7.05706C13.6008 7.31402 13.3974 7.67233 13.3335 8.0667L11.6668 18.0667C11.6287 18.3061 11.6433 18.5509 11.7095 18.784C11.7757 19.0172 11.8919 19.2331 12.0501 19.4167C12.2084 19.6018 12.4054 19.7499 12.6271 19.8507C12.8488 19.9515 13.0899 20.0025 13.3335 20H6.66681C6.26733 19.9949 5.87929 20.1334 5.57337 20.3904C5.26746 20.6474 5.06403 21.0057 5.00014 21.4L3.33348 31.4C3.29537 31.6394 3.30992 31.8842 3.37613 32.1173C3.44233 32.3505 3.55859 32.5664 3.71681 32.75C3.87511 32.9351 4.07204 33.0833 4.29377 33.1841C4.51549 33.2848 4.7566 33.3358 5.00014 33.3334H18.3335C18.5742 33.3334 18.8121 33.2812 19.0308 33.1806C19.2494 33.0799 19.4437 32.933 19.6001 32.75C19.7584 32.5664 19.8746 32.3505 19.9408 32.1173C20.007 31.8842 20.0216 31.6394 19.9835 31.4L18.3168 21.4C18.2533 21.0085 18.0523 20.6525 17.7499 20.3958C17.4475 20.1392 17.0634 19.9989 16.6668 20H23.3335C22.934 19.9949 22.5459 20.1334 22.24 20.3904C21.9341 20.6474 21.7307 21.0057 21.6668 21.4L20.0001 31.4C19.962 31.6394 19.9766 31.8842 20.0428 32.1173C20.109 32.3505 20.2253 32.5664 20.3835 32.75C20.5418 32.9351 20.7387 33.0833 20.9604 33.1841C21.1822 33.2848 21.4233 33.3358 21.6668 33.3334H26.6668C27.1088 33.3334 27.5328 33.1578 27.8453 32.8452C28.1579 32.5327 28.3335 32.1087 28.3335 31.6667C28.3335 31.2247 28.1579 30.8008 27.8453 30.4882C27.5328 30.1756 27.1088 30 26.6668 30H23.6335L24.7501 23.3334H31.9168L33.0335 30H31.6668C31.2248 30 30.8009 30.1756 30.4883 30.4882C30.1757 30.8008 30.0001 31.2247 30.0001 31.6667C30.0001 32.1087 30.1757 32.5327 30.4883 32.8452C30.8009 33.1578 31.2248 33.3334 31.6668 33.3334H35.0001C35.2434 33.3365 35.4843 33.2864 35.7061 33.1865C35.9279 33.0866 36.1252 32.9393 36.284 32.7551C36.4428 32.5708 36.5594 32.3541 36.6255 32.12C36.6917 31.8859 36.7057 31.6402 36.6668 31.4L35.0001 21.4ZM6.96681 30L8.08348 23.3334H15.2501L16.3668 30H6.96681ZM15.3001 16.6667L16.4168 10H23.5835L24.7001 16.6667H15.3001Z" fill="#EB62D0"/>
                    </svg>	
                </div>
                <div  class="chart-num">
                    <h2 class="font-w600 mb-0">
                        @php
                            $founder=App\Models\Founder::where('status', 'paid')->where('user_id', Auth::id());
                        @endphp
                        {{ $general->cur_sym }}{{ getAmount($founder->sum('amount')) }}
                    </h2>
                    <p>
                        <a href=""><span>Founder Bonus</span></a>
                    </p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 text-center">
                    <a href="{{ route('user.report.wallet') }}?walletID="
                        class="btn btn-primary btn-sm">Logs</a>
                </div>
            </div>
        </div> --}}
    </div>
</div>
<div class="row">
    <div class="col-xl-4 wow fadeInUp" data-wow-delay="1.5s">
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img2.png);">
                <h3 class="mt-3 mb-1 text-white">Business Summary</h3>
                <p class="text-white mb-0">Binary Status</p>
                @if (@$user_extras->binary_active == 1)
                    <h3 class="badge badge-success mt-2">Active</h3>
                @else
                    <h3 class="badge badge-danger mt-2">In-Active</h3>
                @endif
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="mb-0">{{ $totalBvCut }} BV</h4>
                        <small>Total Business Volume</small>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ getAmount(@$user_extras->bv_left) }}</h4>
                            <small>Current Left BV</small>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ getAmount(@$user_extras->bv_right) }}</h4>
                            <small>Current Right BV</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 mt-0 text-center">		
                <div class="row">
                    <div class="col-6">
                        <input value="{{ route('user.register') }}?ref={{ auth()->user()->username }}&position=left" type="hidden" id="left" class="form-control">
                        <button class="btn btn-primary btn-block p-3" onclick="copy('left')">Left Link</button>
                    </div>
                    <div class="col-6">
                        <input value="{{ route('user.register') }}?ref={{ auth()->user()->username }}&position=right" type="hidden" id="right" class="form-control">
                        <button class="btn btn-primary btn-block p-3" onclick="copy('right')">Right Link</button>	
                    </div>	
                </div>						
            </div>
        </div>
    </div>
    <div class="col-xl-8 wow fadeInUp" data-wow-delay="1.3s">
        @php
            $now = \Carbon\Carbon::now();
            $created = new \Carbon\Carbon(auth()->user()->check_fairy);
            $rem_days = $commissions[3]->commissionDetail[0]->days - $created->diffInDays($now);
            if($rem_days < 0){
                $rem_days = 0;
            }
        @endphp
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img1.png);">
                <h3 class="mt-3 mb-1 text-white">@if($commissions[3]->name == "Speed Bonus") Flash Bonus @endif</h3>
                <p class="text-white mb-2">Join The Traverse Bot, earn cash back, sponsor 5 members with in 15 days,<br/> unlock earning potential. Financial freedom awaits!</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">The Traverse Bot Flash Bonus rewards new members of The Traverse Bot ecosystem who sponsor 5 members within the first 15 days of joining. These members must have the same or greater value than your joining package.</p>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ (int) $commissions[3]->commissionDetail[0]->direct - @$same_direct->user_count}}</h4>
                            <small>Remaining Members</small>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ getAmount($rem_days) }}</h4>
                            <small>Remaining Days</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 mt-0 text-center">		
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <h6>{{ @$same_direct->user_count / $commissions[3]->commissionDetail[0]->direct * 100 }}%</h6>
                            <span>Direct Members</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ @$same_direct->user_count / $commissions[3]->commissionDetail[0]->direct * 100 }}%"></div>
                        </div>
                    </div>
                </div>						
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-8 wow fadeInUp" data-wow-delay="1.7s">
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img3.png);">
                <h3 class="mt-3 mb-1 text-white">{{ $commissions[4]->name }}</h3>
                <p class="text-white mb-2">Earn 2% share in company's turnover. Qualify as Emerald or above. Monthly payout based on sales volume.</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">The Global Share Bonus is a unique bonus offered by The Traverse Bot that allows all qualified members to earn a 2% share in the company's global turnover. This bonus is available to members who have achieved the rank of Emerald or above.</p>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">Emerald Rank</h4>
                            <small>Requirement</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 wow fadeInUp" data-wow-delay="1.6s">
        @php
            $now = \Carbon\Carbon::now();
            $created = new \Carbon\Carbon(auth()->user()->check_car);
            $rem_day = $commissions[5]->commissionDetail[0]->days - $created->diffInDays($now);
            if($rem_day < 0){
                $rem_day = 0;
            }
        @endphp
        {{-- <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img4.png);">
                <h3 class="mt-3 mb-1 text-white">{{ $commissions[5]->name }}</h3>
                <p class="text-white mb-2">Luxurious Car Bonus from The Traverse Bot based on 30 days production. Earn up to $2,000. Achieve production targets for well-deserved reward.</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">This bonus is based on your 30 days of production and can earn you up to $2,000. To qualify for the Car Bonus, you need to have a 30 days production of at least $25,000, $50,000, or $100,000 in your direct team, depending on the level of the bonus you want to achieve.</p>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">${{ @$direct_sale }}</h4>
                            <small>Current Direct Sale</small>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ $rem_day }}</h4>
                            <small>Remaining Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img4.png);">
                <h3 class="mt-3 mb-1 text-white">Founder's Club Bonus</h3>
                <p class="text-white mb-2">Founder's Club Bonus from The Traverse Bot based on higher plan purchase. Get 1% of the Profit from Traverse Bot.</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">This bonus is a special incentive program offered by our Multi-level-Marketing company to reward the pioneering members who have contributed significantly to the growth and success of our buisness. As part of this program, eligible founders are entitled to receive a 1% share of the company's total profits.</p>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">${{ @$direct_sale }}</h4>
                            <small>Current Direct Sale</small>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ $rem_day }}</h4>
                            <small>Remaining Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        @php
            if ($next_rank) {
                $rem_points = $next_rank->points - getRankPoints(Auth::id());
                if ($rem_points < 0) {
                    $rem_points = 0;
                }
            } else {
                $rem_points = 0;
            }
            
            if (getRankPoints(Auth::id()) < $next_rank->points) {
                $pt = (getRankPoints(Auth::id()) / $next_rank->points) * 100;
            } else {
                $pt = 100;
            }
            
            $tt = round($pt, 1);
            $ts = round($tt / 100, 2);
        @endphp
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
            <div class="card overflow-hidden">
                <div class="text-center p-3" >
                    <h2 class="mt-3 mb-1 text-white">{{ $commissions[6]->name }}</h2>
                </div>
                <div class="card-body text-center">
                    <p><strong class="text-success">{{ $rem_points }} BV</strong> Remaining to Achieve Next Rank</p>
                    <div class="row mt-4">
                        <div class="col text-center">
                            <h5 class="font-weight-semibold mb-1">Current Rank</h5>
                            <p class="mb-2">{{ $rank->name }}</p>
                        </div><!-- col -->
                        <div class="col border-left text-center">
                            <h5 class="font-weight-semibold  mb-1">Next Rank</h5>
                            <p class="mb-2">{{ $next_rank->name }}</p>
                        </div><!-- col -->
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <h6>{{ $tt }}%</h6>
                            <span>{{ $next_rank->name }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ $ts }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    
</div>
<!--Row-->
<div class="row">
    @foreach ($plans as $plan)
        @php
            $created = new \Carbon\Carbon($plan->created_at);
            $updated = new \Carbon\Carbon($plan->updated_at);
            $roi_days = App\Models\CommissionDetail::where('commission_id', 1)->first()->days;
            $rem_days = (float) $roi_days - (float) $plan->roi_limit;
            $now = \Carbon\Carbon::now();
            $days = $difference = $created->diff($now)->days;
            $total_days = $roi_days;
            $lim = ((float) $days / (float) $total_days) * 100;
            $time = $created->diffForHumans($now);
            $expired_time = $updated->diffForHumans($now);
            $now_time = $now->diffInHours($updated);
            $current_limit = $plan->limit_consumed;
            $rem_limit = 100 - (float) $current_limit;
            $show_limit = round((float) $rem_limit / 20) * 20;
            
            if ($rem_limit >= 75 && $rem_limit <= 100) {
                $bg_progress = 'bg-green';
            } elseif ($rem_limit >= 50 && $rem_limit <= 74) {
                $bg_progress = 'bg-teal';
            } elseif ($rem_limit >= 25 && $rem_limit <= 49) {
                $bg_progress = 'bg-info';
            } else {
                $bg_progress = 'bg-red';
            }
            
            $current_date = \Carbon\Carbon::now();
            $release_date = getAmount($general->bal_trans_per_charge);
            
            $roi_status = App\Models\Transaction::where(['commission_id' => 1, 'user_id' => Auth::id(), 'plan_trx' => $plan->trx])
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->count();
            
            $flused_income = App\Models\Transaction::where(['wallet_id' => 6, 'user_id' => Auth::id(), 'plan_trx' => $plan->trx, 'trx_type' => '+'])
                ->whereBetween('created_at', [$updated, $now])
                ->sum('amount');
        @endphp
        {{-- <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3 text-center">
                        <div class="col-md-3 border-right mb-4 mb-md-0">
                            <div class="upgread-stroage">
                                <a href="{{ route('user.plan.index') }}"><h2>{{ $plan->plan->name }}</h2></a>
                                <h3>{{ $general->cur_sym }}{{ getAmount($plan->amount) }}</h3>
                            </div>
                        </div>
                        @if ($plan->is_expired == 0)
                            <div class="col-md-3 border-right mb-4 mb-md-0">
                                <p class="mb-0 text-muted">Purchased</p>
                                <h5 class="mb-0">{{ $time }}</h5>
                            </div>
                            <div class="col-md-3 border-right mb-4 mb-md-0">
                                <p class="mb-0 text-muted">Remaining Daily Days</p>
                                <h5 class="mb-0">{{ $rem_days }}</h5>
                            </div>
                            <div class="col-md-3 text-center">
                                @if (($current_date->dayOfWeek != \Carbon\Carbon::SATURDAY && $current_date->dayOfWeek != \Carbon\Carbon::SUNDAY) && ($roi_status == 0 && $plan->is_roi == 1 && $days > 0))
                                    <a href="javascript:void(0)" class="btn btn-primary"data-bs-target="#confROI{{ $plan->id }}" data-bs-toggle="modal">
                                        Release Daily
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="col-md-3 border-right mb-4 mb-md-0">
                                <p class="mb-0 text-muted">Expired</p>
                                <h5 class="mb-0">{{ $expired_time }}</h5>
                            </div>
                            <div class="col-md-3 border-right mb-4 mb-md-0">
                                <p class="mb-0 text-muted">Flushed Income</p>
                                <h5 class="mb-0">{{ $general->cur_sym }}{{ getAmount($flused_income) }}</h5>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mb-0 text-muted">Renew Now</p>
                                <button type="button" class="btn btn-primary"
                                    data-bs-target="#confRenewModal{{ $plan->id }}"
                                    data-bs-toggle="modal">Renew</button>
                                <!-- Modal -->
                                <div class="modal" id="confRenewModal{{ $plan->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="confRenewModal{{ $plan->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confRenewModal{{ $plan->id }}">
                                                    @lang('Confirm Renew ' . $plan->plan->name)?
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form method="post" action="{{ route('user.plan.renew') }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <p class="text-center">
                                                        {{ getAmount($plan->plan->price) }}
                                                        {{ $general->cur_text }} @lang('will subtract from your wallet')</p>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <select id="wallet_id" class="form-control form-control-lg" name="wallet_id">
                                                                    <option selected disabled>Select Wallet</option>
                                                                    @foreach ($wallets as $wallet)
                                                                        @if ($wallet->wallet->display)
                                                                            <option value="{{ $wallet->wallet->id }}">{{ $wallet->wallet->name }} -
                                                                                {{ getAmount($wallet->balance) }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="plan_id"
                                                        value="{{ $plan->id }}"
                                                        class="btn btn-primary float-end"><i
                                                            class="fa fa-telegram"></i>
                                                        @lang('Renew')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="">
                        @if ($plan->is_expired == 0)
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <h6>{{ round($rem_limit) }}%</h6>
                                    <span>Network Limit</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: {{ round($rem_limit) }}%"></div>
                                </div>
                            </div>
                        @else
                            @if ($now_time <= 24)
                                <p class="mb-1 text-center">Your Package has been <strong>expired</strong>. Renew your
                                    package with in <strong>{{ 24 - $now_time }} hours</strong> to get back your flused
                                    income <strong>{{ $general->cur_sym }}{{ getAmount($flused_income) }}</strong>.
                                </p>
                            @endif
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <h6>{{ round($rem_limit) }}%</h6>
                                    <span>Expired</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" style="width: {{ round($rem_limit) }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="row">
            <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
                <div class="card overflow-hidden">
                    <div class="text-center p-3" >
                        <h2 class="mt-3 mb-1 text-white">MM OTT</h2>
                    </div>
                    <div class="card-body text-center">
                        <a href="http://bit.ly/mmott223" target="_blank"><h3>Click to download the application.</h3></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Modal -->
        {{-- <div class="modal" id="confROI{{ $plan->id }}" tabindex="-1" role="dialog"
            aria-labelledby="confROI{{ $plan->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confROI{{ $plan->id }}">@lang('Confirm Release Daily for ' . $plan->plan->name)?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <form id="form{{ $plan->id }}" method="post"
                        action="{{ route('user.roi.compound') }}?trx={{ $plan->trx }}">
                        @csrf
                        <div class="modal-body">
                            <p class="text-center"> @lang('Do you want to Release Daily?')</p>
                        </div>
                        <div class="modal-footer myhide">
                            <button type="submit" name="compounding" class="btn btn-primary"
                                value="1">@lang('Yes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    @endforeach
</div>
<!--End row-->
@if(count($purchased_plans) > 0)
    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            
            <div class="card overflow-hidden">
                <div class="card-header" style="display:block;">
                    <h1 class="text-center">Support Plans</h1>
                </div>
                <div class="card-body pb-0 mb-4">
                    <div class="row text-center">
                        @foreach ($plans as $plan)
                        @if ($plan->type == "sponsor")
                            {{-- <div class="col-md-4 mt-4 mb-2">
                                <div class="bgl-primary rounded p-2 pt-4">
                                    <p class="text-white"><strong>{{ $plan->plan->name }}</strong>: {{ $general->cur_sym }}{{ $plan->amount }}</p>
                                </div>
                            </div> --}}
                            <div class="row mt-4">
                                <div class="col text-center">
                                    <h5 class="font-weight-semibold mb-1">Plan Name</h5>
                                    <p class="mb-2">{{ $plan->plan->name }}</p>
                                </div><!-- col -->
                                <div class="col border-left text-center">
                                    <h5 class="font-weight-semibold  mb-1">Plan Amount</h5>
                                    <p class="mb-2">{{ $general->cur_sym }}{{ $plan->amount }}</p>
                                </div><!-- col -->
                            </div>
                        @endif
                        @endforeach
                    </div>
                    {{-- <div class="card-footer pt-0 pb-0 text-center">
                        <div class="row mt-4">
                        </div>
                    </div> --}}
                </div>  
            </div>
        </div>
    </div>
@endif
@endsection

@push('script')
    <script>
        var copy = function(elementId) {

            var input = document.getElementById(elementId);
            var isiOSDevice = navigator.userAgent.match(/ipad|iphone/i);
            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = input.value;
            tempInput.readOnly = true;
            document.body.appendChild(tempInput);
            if (isiOSDevice) {

                var editable = tempInput.contentEditable;
                var readOnly = tempInput.readOnly;

                tempInput.contentEditable = true;
                tempInput.readOnly = false;

                var range = document.createRange();
                range.selectNodeContents(tempInput);

                var selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);

                tempInput.setSelectionRange(0, 999999);
                tempInput.contentEditable = editable;
                tempInput.readOnly = readOnly;

            } else {
                tempInput.select();
            }
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            notify('success', 'Copied successfully');
        };
    </script>

    @php
        $date = today()->format('Y-m-d');
        $notifications = \App\Models\Notification::whereRaw('type = 1 and show_type = 0 and till_date >= ?', [$date])->get();
    @endphp
    @foreach ($notifications as $notification)
        <div class="myModal{{ $notification->id }} modal center-modal fade" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white">@lang('Notification')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ url('/') }}/assets/images/notifications/{{ $notification->image }}"
                            style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $(".myModal{{ $notification->id }}").modal('show');
            });
        </script>
    @endforeach
@endpush
