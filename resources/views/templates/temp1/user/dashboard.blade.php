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
            <div class="card-body pb-0">
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
            </div>
            <div class="chart-wrapper">
                <div id="chart_widget_5"></div>
                <div class="px-4"><span class="peity-line" data-width="100%">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
            </div>
            <div class="card-footer pt-0 pb-0 text-center">
                <div class="row mt-4">
                @foreach ($commissions as $commission)
                    @if($commission->id != 7)
                        <div class="col-3 pt-3 pb-3 border-end">
                            <h3 class="mb-1">
                                @if($commission->id == 1)
                                {{ $general->cur_sym }}{{ getAmount(App\Models\Transaction::where('commission_id', $commission->id)->where('user_id', Auth::id())->sum('amount')) }}
                                @else
                                {{ $general->cur_sym }}{{ getAmount(App\Models\Transaction::where('commission_id', $commission->id)->where('wallet_id', '!=', 6)->where('user_id', Auth::id())->sum('amount')) }}
                                @endif
                            </h3>
                            <a href="{{ route('user.report.commission') }}?commissionID={{ $commission->id }}"><span>{{ $commission->name }}</span></a>
                        </div>
                    @endif
                @endforeach
                <div class="col-3 pt-3 pb-3 border-end">
                    <h3 class="mb-1">
                        @php
                            $founder=App\Models\Founder::where('status', 'paid')->where('user_id', Auth::id());
                        @endphp
                        {{ $general->cur_sym }}{{ getAmount($founder->sum('amount')) }}
                    </h3>
                    <a href=""><span>Founder Bonus</span></a>
                </div>
                </div>
            </div>
        </div>
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
                <h3 class="mt-3 mb-1 text-white">{{ $commissions[3]->name }}</h3>
                <p class="text-white mb-2">Join The Millionaires Multiverse, earn cash back, sponsor 5 members with in 15 days,<br/> unlock earning potential. Financial freedom awaits!</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">The Millionaires Multiverse Speed Bonus rewards new members of The Millionaires Multiverse ecosystem who sponsor 5 members within the first 15 days of joining. These members must have the same or greater value than your joining package.</p>
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
    <div class="col-xl-6 wow fadeInUp" data-wow-delay="1.7s">
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img3.png);">
                <h3 class="mt-3 mb-1 text-white">{{ $commissions[4]->name }}</h3>
                <p class="text-white mb-2">Earn 2% share in company's turnover. Qualify as Emerald or above. Monthly payout based on sales volume.</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">The Global Share Bonus is a unique bonus offered by The Millionaires Multiverse that allows all qualified members to earn a 2% share in the company's global turnover. This bonus is available to members who have achieved the rank of Emerald or above.</p>
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
    <div class="col-xl-6 wow fadeInUp" data-wow-delay="1.6s">
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
                <p class="text-white mb-2">Luxurious Car Bonus from The Millionaires Multiverse based on 30 days production. Earn up to $2,000. Achieve production targets for well-deserved reward.</p>
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
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img4.png);">
                <h3 class="mt-3 mb-1 text-white">Founder's Club Bonus</h3>
                <p class="text-white mb-2">Founder's Club Bonus from The Millionaires Multiverse based on higher plan purchase. Get 1% of the Profit from Millionaires Multiverse.</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">This bonus is a special incentive program offered by our Multi-level-Marketing company to reward the pioneering members who have contributed significantly to the growth and success of our buisness. As part of this program, eligible founders are entitled to receive a 1% share of the company's total profits.</p>
                    </div>
                    {{-- <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">${{ @$direct_sale }}</h4>
                            <small>Current Direct Sale</small>
                        </div>
                    </div> --}}
                    {{-- <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ $rem_day }}</h4>
                            <small>Remaining Days</small>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
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
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
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
                                @if (($current_date->dayOfWeekIso != \Carbon\Carbon::SATURDAY || $current_date->dayOfWeekIso != \Carbon\Carbon::SUNDAY) && ($roi_status == 0 && $plan->is_roi == 1 && $days > 0))
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
        </div>

        <!-- Modal -->
        <div class="modal" id="confROI{{ $plan->id }}" tabindex="-1" role="dialog"
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
        </div>
    @endforeach
</div>
<!--End row-->

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
