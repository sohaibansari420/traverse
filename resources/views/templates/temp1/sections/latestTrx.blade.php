@php
    $latestTrx = getContent('latestTrx.content', true);
    $deposits = App\Models\Deposit::latest()->where('status', 1)->take(10)->with('user')->get();
    $withdraws = App\Models\Withdrawal::latest()->where('status', 1)->take(10)->with('user')->get();
@endphp

<section class="transaction-section padding-top section-bg padding-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header">
                    <h2 class="title">@lang(@$latestTrx->data_values->heading)</h2>
                    <p>@lang(@$latestTrx->data_values->sub_heading)</p>
                </div>
            </div>
        </div>
        <div class="tab deposit-tab">
            <ul class="tab-menu text-center">
                <li class="active custom-button">@lang('Latest Deposits')</li>
                <li class="custom-button">@lang('Latest Withdraws')</li>
            </ul>
            <div class="tab-area">
                <div class="tab-item active">
                    <div class="deposite-table">
                        <table>
                            <thead>
                            <tr class="bg-2">
                                <th>@lang('Name')</th>
                                <th>@lang('Plan')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($deposits as $deposit)
                                <tr>
                                    <td data-input="@lang('Name')">
                                        <div class="author">
                                            <div class="thumb">
                                                <img src="{{getImage('assets/images/user/profile/' . @$deposit->user->image, '150x150')}}" alt="jpg">
                                            </div>
                                            <div class="content">{{@$deposit->user->fullName}}</div>
                                        </div>
                                    </td>
                                    <td data-input="@lang('Plan')">{{@$deposit->user->plan->name ?? 'No plan'}}</td>
                                    <td data-input="@lang('Date')">{{showDateTime($deposit->created_at , $format = 'd F, Y')}}</td>
                                    <td data-input="@lang('Amount')">{{getAmount($deposit->amount)}} {{$general->cur_text}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-item">
                    <div class="deposite-table">
                        <table>
                            <thead>
                            <tr class="bg-2">
                                <th>@lang('Name')</th>
                                <th>@lang('Plan')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdraws as $withdraw)
                                <tr>
                                    <td data-input="@lang('Name')">
                                        <div class="author">
                                            <div class="thumb">
                                                <img
                                                    src="{{getImage('assets/images/user/profile/' . @$withdraw->user->image, '150x150')}}"
                                                    alt="jpg">
                                            </div>
                                            <div class="content">
                                                {{@$withdraw->user->fullName}}
                                            </div>
                                        </div>
                                    </td>
                                    <td data-input="@lang('Plan')">{{@$deposit->user->plan->name ?? 'No plan'}}</td>
                                    <td data-input="@lang('Date')">{{showDateTime($withdraw->created_at , $format = 'd F, Y')}}</td>
                                    <td data-input="@lang('Amount')">{{getAmount($withdraw->amount)}} {{$general->cur_text}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
