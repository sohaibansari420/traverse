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
    <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img2.png);">
                <h3 class="mt-3 mb-1 text-white">Business Summary</h3>
                <p class="text-white mb-0">Binary Status</p>
                @if (@$user_extras->binary_active == 1)
                    <h3 class="badge badge-success mt-2">Active</h3>
                @else
                    <h3 class="badge badge-danger mt-2">inactive</h3>
                @endif
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="mb-0">{{ $totalBvCut }} BV</h4>
                        <small>Total Business Volume</small>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="input-group">
                            <span class="input-group-text" id="char-count">{{ getAmount(@$user_extras->bv_left) }}</span>
                            <input type="text" readonly name="left" class="form-control" placeholder="Enter something" id="left" value="{{ route('user.register') }}?ref={{ auth()->user()->username }}&position=left">
                            <button class="btn btn-black" onclick="copy('left')" style="border: 2px solid #945194c4; color: white;">Left Link</button>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="input-group">
                            <span class="input-group-text" id="char-count">{{ getAmount(@$user_extras->bv_right) }}</span>
                            <input type="text" readonly name="right" class="form-control" placeholder="Enter something" id="right" value="{{ route('user.register') }}?ref={{ auth()->user()->username }}&position=right">
                            <button class="btn btn-black" onclick="copy('right')" style="border: 2px solid #945194c4; color: white;">Right Link</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
        <div class="card overflow-hidden">
            {{-- <div class="card-header" style="display:block;">
                <h1 class="text-center">Balances & Bonuses!!!</h1>
            </div> --}}
            <div class="card-body pb-0 pt-0 mb-2">
                <div class="row text-center">
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
            <div class="card-footer pt-0 pb-0 text-center">
                <div class="row mt-3">
                    <div class="col-xl-12 row wow fadeInUp p-0 m-0" data-wow-delay="1.2s">
                        @foreach ($commissions as $commission)
                            @if($commission->id != 7)
                                <div class="col-md-4">
                                    <div class="card counter" style="background: #322f62;">
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
                                            <div class="ms-auto mt-3">
                                                <a href="{{ route('user.report.commission') }}?commissionID={{ $commission->id }}"
                                                    class="btn btn-primary btn-sm">Report</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div clsss="row" style="display: flex;">
    <div class="col-xl-12">
        <div class="wow fadeInUp" data-wow-delay="1.3s">
            @php
                $now = \Carbon\Carbon::now();
                $created = new \Carbon\Carbon(auth()->user()->check_fairy);
                $rem_days = $commissions[3]->commissionDetail[0]->days - $created->diffInDays($now);
                if($rem_days < 0){
                    $rem_days = 0;
                }
            @endphp
            @php
                $progressDays = 15;
                $progressMembers = 5;
                $finalProgress = 3; // Average progress
                $strokeDashoffset = 3;
            @endphp
            <div class="card overflow-hidden">
                <div class="text-center overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img1.png);">
                    <h3 class="mt-3 mb-1 text-white">{{ $commissions[3]->name }}</h3><br>
                    {{-- <p class="text-white mb-2">Join The Traverse Bot, earn cash back, sponsor 5 members with in 15 days,<br/> unlock earning potential. Financial freedom awaits!</p> --}}
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12">
                            <p class="mb-0 fs-15">The Traverse Bot Cash Back Bonus rewards new members of The Traverse Bot ecosystem who sponsor 5 members within the first 15 days of joining. These members must have the same or greater value than your joining package.</p>
                        </div>
                        <div class="col-6 mt-4">
                            <div class="bgl-primary rounded">
                                <h4 class="mb-0">{{ (int) $commissions[3]->commissionDetail[0]->direct - @$same_direct->user_count}}</h4>
                                <small>Remaining Members</small>
                            </div>
                        </div>
                        <div class="col-6 mt-4">
                            <div class="bgl-primary rounded">
                                <h4 class="mb-0">{{ getAmount($rem_days) }}</h4>
                                <small>Remaining Days</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 mt-0 text-center pt-0">		
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
        {{-- <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.6s">
            @php
                $now = \Carbon\Carbon::now();
                $created = new \Carbon\Carbon(auth()->user()->check_car);
                $rem_day = $commissions[5]->commissionDetail[0]->days - $created->diffInDays($now);
                if($rem_day < 0){
                    $rem_day = 0;
                }
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
                <div class="card overflow-hidden ">
                    <div class="text-center" >
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
        </div> --}}
    </div>
</div>
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
