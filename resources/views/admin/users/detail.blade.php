@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">

        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage('assets/images/user/profile/' . $user->image, '350x300') }}"
                                alt="@lang('profile-image')" class="b-radius--10 w-100">
                        </div>
                        <div class="mt-15">
                            <h4 class="">{{ $user->fullname }}</h4>
                            <span
                                class="text--small">@lang('Joined At ')<strong>{{ showDateTime($user->created_at, 'd M, Y h:i A') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{ $user->username }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Ref By')
                            <span class="font-weight-bold"> {{ $ref_id->username ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Legacy Email')
                            <span class="font-weight-bold"> {{ $user->legacy_email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Position')
                            <span class="font-weight-bold">
                                @if ($user->position == 1)
                                    Left
                                @elseif($user->position == 2)
                                    Right
                                @else
                                    Root
                                @endif
                            </span>
                        </li>
                        @php
                            $user_plans = \App\Models\PurchasedPlan::where('user_id', $user->id)
                                ->orderBy('id', 'desc')
                                ->get();
                        @endphp
                        @forelse($user_plans as $user_plan)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Plan')
                                <span class="font-weight-bold">
                                    {{ @$user_plan->plan->name }}({{ @getAmount($user_plan->plan->price) }})</span>
                            </li>
                        @empty
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Plan')
                                <span class="font-weight-bold">Not Purchased</span>
                            </li>
                        @endforelse

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Rank')
                            <span class="font-weight-bold">{{ $rank }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Left Binary')
                            <span class="font-weight-bold">{{ @$user_extras->lb }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Right Binary')
                            <span class="font-weight-bold">{{ @$user_extras->rb }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Binary Active')
                            <span class="font-weight-bold">{{ @$user_extras->binary_active }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Total BV')
                            <span class="font-weight-bold"><a href="{{ route('admin.report.single.bvLog', $user->id) }}">
                                    {{ getAmount(@$user->userExtra->bv_left + @$user->userExtra->bv_right) }} </a></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Paid Left User')
                            <span class="font-weight-bold">{{ @$user->userExtra->paid_left }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Paid Right User')
                            <span class="font-weight-bold">{{ @$user->userExtra->paid_right }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Free Left User')
                            <span class="font-weight-bold">{{ @$user->userExtra->free_left }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Free Right User')
                            <span class="font-weight-bold">{{ @$user->userExtra->free_right }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @switch($user->status)
                                @case(1)
                                    <span class="badge badge-pill bg--success">@lang('Active')</span>
                                @break

                                @case(2)
                                    <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                                @break
                            @endswitch
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Code')
                            <span class="font-weight-bold"> {{ $user->ver_code }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User action')</h5>
                    <a data-toggle="modal" href="#addSubModal" class="btn btn--success btn--shadow btn-block btn-lg">
                        @lang('Add/Subtract Cash')
                    </a>
                    <a href="{{ route('admin.users.login.history.single', $user->id) }}"
                        class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{ route('admin.users.email.single', $user->id) }}"
                        class="btn btn--danger btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
                    <a href="{{ route('admin.users.single.tree', $user->username) }}"
                        class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('User Tree')
                    </a>
                    <a href="{{ route('admin.users.ref', $user->id) }}" class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('User Referrals')
                    </a>
                    <a href="{{ route('admin.users.password.change', $user->id) }}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Password Change')
                    </a>
                    <a href="{{ route('admin.users.login', $user->id) }}" target="_blank"
                        class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as User')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            <div class="row mb-none-30">

                @foreach ($user_wallets as $key => $user_wallet)
                    <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                        <div class="dashboard-w1 bg--{{ $key + 1 }} b-radius--10 box-shadow has--link">
                            <a href="{{ route('admin.report.wallet') }}?walletID={{ $user_wallet->wallet_id }}&userID={{ $user->id }}"
                                class="item--link"></a>
                            <div class="icon">
                                <i class="la la-wallet"></i>
                            </div>
                            <div class="details">
                                <div class="numbers">
                                    <span class="amount">{{ getAmount($user_wallet->balance) }}</span>
                                    <span class="currency-sign">{{ $general->cur_text }}</span>
                                </div>
                                <div class="desciption">
                                    <span>{{ $user_wallet->wallet->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--primary b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.users.deposits', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ getAmount($totalDeposit) }}</span>
                                <span class="currency-sign">{{ $general->cur_text }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Deposit')</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--red b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.users.withdrawals', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="fa fa-wallet"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ getAmount($totalWithdraw) }}</span>
                                <span class="currency-sign">{{ $general->cur_text }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Withdraw')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- dashboard-w1 end -->

                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--dark b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.users.transactions', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ $totalTransaction }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Transaction')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->


                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--info b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.report.invest') }}?user={{ $user->id }}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-money"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span
                                    class="amount">{{ getAmount(App\Models\Transaction::where('remark', 'purchased_plan')->where('user_id', $user->id)->sum('amount')) }}</span>
                                <span class="currency-sign">{{ $general->cur_text }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Invest')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--info b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.report.plan.purchased', 'all') }}?userID={{ $user->id }}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-money"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span
                                    class="amount">{{ getAmount(App\Models\PurchasedPlan::where('user_id', $user->id)->count()) }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Purchased Plans')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--15 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.report.single.bvLog', $user->id) }}?type=leftBV" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-arrow-alt-circle-left"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ getAmount(@$user->userExtra->bv_left) }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Left BV')</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--12 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.report.single.bvLog', $user->id) }}?type=rightBV"
                            class="item--link"></a>
                        <div class="icon">
                            <i class="las la-arrow-alt-circle-right"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ getAmount(@$user->userExtra->bv_right) }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Right BV')</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--2 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.report.single.bvLog', $user->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-user"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ getAmount(@$user->total_bv) }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total BV Cut')</span>
                            </div>
                        </div>
                    </div>
                </div>



                @foreach ($commissions as $key => $commission)
                    <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                        <div class="dashboard-w1 bg--{{ $key + 1 }} b-radius--10 box-shadow has--link">
                            <a href="{{ route('admin.report.commission') }}?commissionID={{ $commission->id }}&userID={{ $user->id }}"
                                class="item--link"></a>
                            <div class="icon">
                                <i class="la la-link"></i>
                            </div>
                            <div class="details">
                                <div class="numbers">
                                    <span
                                        class="amount">{{ getAmount(\App\Models\Transaction::where('commission_id', $commission->id)->where('user_id', $user->id)->sum('amount')) }}</span>
                                    <span class="currency-sign">{{ $general->cur_text }}</span>
                                </div>
                                <div class="desciption">
                                    <span>{{ $commission->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach




            </div>


            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('KYC Verification')</h5>
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <strong>Status: @if ($user->verify == 0)
                                    Unverified
                                @elseif($user->verify == 1)
                                    Pending
                                @elseif($user->verify == 2)
                                    Verified
                                @endif </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img style="width: 100%; height: 300px"
                                src="{{ url('/') }}/assets/images/user/kyc/{{ $user->front }}" alt="Front Image">
                        </div>
                        <div class="col-md-4">
                            <img style="width: 100%; height: 300px"
                                src="{{ url('/') }}/assets/images/user/kyc/{{ $user->back }}" alt="Back Image">
                        </div>
                        <div class="col-md-4">
                            <img style="width: 100%; height: 300px"
                                src="{{ url('/') }}/assets/images/user/kyc/{{ $user->selfie }}" alt="Front Image">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <form action="{{ route('admin.users.verify.update', $user->id) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" rows="5">Congratulations, {{ $general->sitename }} has confirmed your identity. Good luck with your earnings. </textarea>
                                        </div>
                                        <button type="submit" name="verify" class="btn btn-success btn-block"
                                            value="2">Approve</button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message2" rows="5">Your identity has not been verified due to </textarea>
                                        </div>
                                        <button type="submit" name="verify" class="btn btn-danger btn-block"
                                            value="0">Reject</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Plan Activation')</h5>
                    <form action="{{ route('admin.user.plan.active', $user->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-row">
                            <div class="col-md-4">
                                <input type="hidden" name="res" value="0">
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    @php
                                        $plans = \App\Models\Plan::where('status', 1)->get();
                                    @endphp
                                    <label class="col-md-12"><strong>Select Plan</strong></label>
                                    <div class="col-md-12">
                                        <select class="form-control form-control-inverse btn-square pay" name="plan">
                                            <option selected disabled>Select Plan</option>
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->name }} -
                                                    {{ $plan->price }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="send_bv">
                                    <label class="form-check-label" for="exampleCheck1">With Points</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck2" name="send_roi">
                                    <label class="form-check-label" for="exampleCheck1">With Roi</label>
                                </div>
                            </div>


                        </div><!-- row -->

                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-block btn-lg">Active Plan</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Information')</h5>

                    <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ $user->firstname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ $user->lastname }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile"
                                        value="{{ $user->mobile }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('BTC Wallet') </label>
                                    <input class="form-control" type="text" name="btc_wallet"
                                        value="{{ $user->btc_wallet }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Trc20 Wallet') </label>
                                    <input class="form-control" type="text" name="trc20_wallet"
                                        value="{{ $user->trc20_wallet }}">
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ $user->address->address }}">
                                    <small class="form-text text-muted"><i class="las la-info-circle"></i>
                                        @lang('House number, street address')
                                    </small>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city"
                                        value="{{ $user->address->city }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state"
                                        value="{{ $user->address->state }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip"
                                        value="{{ $user->address->zip }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') </label>
                                    <select name="country" class="form-control"> @include('partials.country') </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="Active" data-off="Banned" data-width="100%"
                                    name="status" @if ($user->status) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="Verified" data-off="Unverified" name="ev"
                                    @if ($user->ev) checked @endif>

                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="Verified" data-off="Unverified" name="sv"
                                    @if ($user->sv) checked @endif>

                            </div>
                            <div class="form-group  col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="Active" data-off="Deactive" name="ts"
                                    @if ($user->ts) checked @endif>
                            </div>

                            <div class="form-group  col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="Verified" data-off="Unverified" name="tv"
                                    @if ($user->tv) checked @endif>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Add Sub Cash MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add / Subtract Cash')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.users.addSubBalance', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="checkbox" data-width="100%" data-height="44px" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="Add Cash"
                                    data-off="Subtract Cash" name="act" checked>
                            </div>


                            <div class="form-group col-md-12">
                                <label>@lang('Amount')<span class="text-danger">*</span></label>
                                <div class="input-group has_append">
                                    <input type="text" name="amount" class="form-control"
                                        placeholder="Please provide positive amount">
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ $general->cur_text }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $("select[name=country]").val("{{ @$user->address->country }}");
        })(jQuery)
    </script>
@endpush
