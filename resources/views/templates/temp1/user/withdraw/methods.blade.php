@extends($activeTemplate . 'user.layouts.app')
@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">Withdraw</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!--Row-->
                    <div class="row mt-4 justify-content-center">
                        <div class="col-12 text-center">
        
                        </div>
                        @if (Auth::user()->plan_purchased)
                            @if(getAmount(app('request')->input('walletID')) == 3)
                                @if(checkSponsorWithdraw(Auth::id()) == 1)
                                    @foreach ($withdrawMethod as $data)
                                        <div class="col-lg-4 col-sm-12 p-l-0 p-r-0">
                                            <div class="card text-center">
    
                                                <div class="widget-line mt-4">
                                                    <h2>Withdraw via {{ __($data->name) }}</h2>
                                                </div>
                                                <div class="mx-auto chart-circle chart-circle-md mt-2" data-value="0.55"
                                                    data-thickness="20" data-color="#38a01e">
                                                    <div class="chart-circle-value fs">
                                                        <img src="{{ getImage(imagePath()['withdraw']['method']['path'] . '/' . $data->image) }}"
                                                            class="box-img-top depo" alt="{{ __($data->name) }}">
                                                    </div>
                                                </div>
                                                <ul class="list-group text-center font-15 mt-4">
                                                    <li class="list-group-item ">@lang('Limit')
                                                        : {{ getAmount($data->min_limit) }}
                                                        - {{ getAmount($data->max_limit) }} {{ $general->cur_text }}</li>
                                                    <li class="list-group-item "> @lang('Charges')
                                                        - {{ getAmount($data->percent_charge) }}%
                                                    </li>
                                                    <li class="list-group-item">@lang('Processing Time')
                                                        - {{ $data->delay }}</li>
                                                    
                                                </ul>
                                                <div class="row justify-content-center mt-5 mb-5">
    
                                                    <div class="col-11">
                                                        @if (@$created_at && $remaining > 0)
                                                            <h4 id="note"></h4>
                                                        @else
                                                            <a href="javascript:void(0)" type="button"
                                                                data-id="{{ $data->id }}" data-resource="{{ $data }}"
                                                                data-min_amount="{{ getAmount($data->min_limit) }}"
                                                                data-max_amount="{{ getAmount($data->max_limit) }}"
                                                                data-fix_charge="{{ getAmount($data->fixed_charge) }}"
                                                                data-percent_charge="{{ getAmount($data->percent_charge) }}"
                                                                data-wallet_id="{{ $wallet_id }}"
                                                                data-base_symbol="{{ $general->cur_text }}"
                                                                data-wallet_balance="{{ $wallet_balance }}"
                                                                class=" btn btn-primary btn-block deposit" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal">
                                                                @lang('Withdraw Now')</a>
                                                            {{-- <div class="form-group">
                                                                <label>@lang('Enter Amount'):</label>
                                                                <div class="input-group p-2">
                                                                    <input id="amount" type="text" class="form-control form-control-lg"
                                                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                                        name="amount" placeholder="0.00" required value="{{ old('amount') }}">
                                                                </div>
                                                            </div>
                                                            <div class="mt-auto text-center">
                                                                <button class="btn btn-outline-info rounded-pill px-4" id="withdrawBtn">
                                                                    <i class="fas fa-shopping-cart me-2"></i>Withdraw Now
                                                                </button>
                                                            </div> --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                     @php
                                        $plan = App\Models\PurchasedPlan::where('user_id', Auth::id())->where('type', 'sponsor')->first();
                                        if($plan->plan_limit != null){
                                            $planLimit = $plan->plan_limit;
                                        }
                                        else{
                                            $planLimit = $general->user1_detail;
                                        }
                                     @endphp   
                                    <h3>To get withdraw with promotional account. You need to join ${{$planLimit * $plan->amount}} direct sale.</h3>
                                @endif
                            @else
                                @foreach ($withdrawMethod as $data)
                                    <div class="col-lg-4 col-sm-12 p-l-0 p-r-0">
                                        <div class="card text-center">

                                            <div class="widget-line mt-4">
                                                <h2>Withdraw via {{ __($data->name) }}</h2>
                                            </div>
                                            <div class="mx-auto chart-circle chart-circle-md mt-2" data-value="0.55"
                                                data-thickness="20" data-color="#38a01e">
                                                <div class="chart-circle-value fs">
                                                    <img src="{{ getImage(imagePath()['withdraw']['method']['path'] . '/' . $data->image) }}"
                                                        class="box-img-top depo" alt="{{ __($data->name) }}">
                                                </div>
                                            </div>
                                            <ul class="list-group text-center font-15 mt-4">
                                                <li class="list-group-item ">@lang('Limit')
                                                    : {{ getAmount($data->min_limit) }}
                                                    - {{ getAmount($data->max_limit) }} {{ $general->cur_text }}</li>
                                                <li class="list-group-item "> @lang('Charges')
                                                    - {{ getAmount($data->percent_charge) }}%
                                                </li>
                                                <li class="list-group-item">@lang('Processing Time')
                                                    - {{ $data->delay }}</li>
                                                
                                            </ul>
                                            <div class="row justify-content-center mt-5 mb-5">

                                                <div class="col-11">
                                                    @if (@$created_at && $remaining > 0)
                                                        <h4 id="note"></h4>
                                                    @else
                                                        <a href="javascript:void(0)" type="button"
                                                            data-id="{{ $data->id }}" data-resource="{{ $data }}"
                                                            data-min_amount="{{ getAmount($data->min_limit) }}"
                                                            data-max_amount="{{ getAmount($data->max_limit) }}"
                                                            data-fix_charge="{{ getAmount($data->fixed_charge) }}"
                                                            data-percent_charge="{{ getAmount($data->percent_charge) }}"
                                                            data-wallet_id="{{ $wallet_id }}"
                                                            data-base_symbol="{{ $general->cur_text }}"
                                                            data-wallet_balance="{{ $wallet_balance }}"
                                                            class=" btn btn-primary btn-block deposit" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal">
                                                            @lang('Withdraw Now')</a>
                                                         {{-- <div class="form-group">
                                                            <label>@lang('Enter Amount'):</label>
                                                            <div class="input-group p-2">
                                                                <input id="amount" type="text" class="form-control form-control-lg"
                                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                                    name="amount" placeholder="0.00" required value="{{ old('amount') }}">
                                                            </div>
                                                        </div>
                                                        <div class="mt-auto text-center">
                                                            <button class="btn btn-outline-info rounded-pill px-4" id="withdrawBtn">
                                                                <i class="fas fa-shopping-cart me-2"></i>Withdraw Now
                                                            </button>
                                                        </div>     --}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @else
                            <h3>Kindly buy a Package First</h3>
                        @endif
                    </div>
                    <!--End row-->

                    <!-- Modal -->
                    <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title method-name" id="exampleModal"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <form action="{{ route('user.withdraw.money') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="currency" class="edit-currency form-control"
                                                value="">
                                            <input type="hidden" name="wallet_id" class="edit-wallet_id form-control"
                                                value="">
                                            <input type="hidden" name="method_code" class="edit-method-code  form-control"
                                                value="">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Enter Amount'):</label>
                                            <div class="input-group">
                                                <input id="amount" type="text" class="edit-wallet_balance form-control form-control-lg"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    name="amount" placeholder="0.00" required=""
                                                    value="{{ old('amount') }}">
                                            </div>
                                        </div>
                                        <p class="text-danger text-center depositLimit"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">@lang('Close')</button>
                                        <button type="submit" class="btn btn-success">@lang('Confirm')</button>
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
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
  <script>
    // const walletID = {{ $wallet_id }}
    // $('#withdrawBtn').click(async function() {
    //     var $btn = $(this);
    //     var amount = $('#amount').val();

    //     if (!amount || amount < 20) {
    //         alert('Please enter wallet and amount');
    //         return;
    //     }

    //     // Check for MetaMask / Ethereum provider
    //     if (!window.ethereum) {
    //         alert('Token Pocket or compatible wallet not detected!');
    //         return;
    //     }

    //     if ($btn.prop('disabled')) return;
    //     $btn.prop('disabled', true).text('Processing...');  

    //     try {
    //         // Request account access
    //         const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
    //         const userAccount = accounts[0];

    //         // Send AJAX request with amount and user wallet
    //         $.ajax({
    //             url: "{{ route('user.withdraw.WalletWEBWithdrawal') }}",
    //             type: 'POST',
    //             data: {
    //                 _token: '{{ csrf_token() }}',
    //                 amount: amount,
    //                 walletID : walletID,
    //                 wallet: userAccount
    //             },
    //             success: function(res) {
    //                 alert(res.success || res.error || 'Transaction sent');
    //             },
    //             error: function(xhr) {
    //                 alert(xhr.responseJSON?.error || 'Transaction failed');
    //             }
    //         });
    //     } catch (err) {
    //         console.error(err);
    //         alert('User denied wallet connection');
    //     }
    //     finally {
    //         // Re-enable button
    //         $btn.prop('disabled', false).text('Withdraw');
    //     }
    // });
</script>    
<script>
        'use strict';
        (function($) {
            $('.deposit').on('click', function() {
                var id = $(this).data('id');
                var wallet_id = $(this).data('wallet_id');
                var wallet_balance = $(this).data('wallet_balance');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Withdraw Limit:') ${minAmount} - ${maxAmount}  {{ $general->cur_text }}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge =
                    `@lang('Charge:') ${fixCharge} {{ $general->cur_text }} ${(0 < percentCharge) ? ' + ' + percentCharge + ' %' : ''}`
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Withdraw Via ') ${result.name}`);
                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.id);
                $('.edit-wallet_id').val(wallet_id);
                $('.edit-wallet_balance').val(wallet_balance);
            });
        })(jQuery)
    </script>
    <script>
        (function($) {

            // Number of seconds in every time division
            var days = 24 * 60 * 60,
                hours = 60 * 60,
                minutes = 60;

            // Creating the plugin
            $.fn.countdown = function(prop) {

                var options = $.extend({
                    callback: function() {},
                    timestamp: 0
                }, prop);

                var left, d, h, m, s, positions;

                // Initialize the plugin
                init(this, options);

                positions = this.find('.position');

                (function tick() {

                    // Time left
                    left = Math.floor((options.timestamp - (new Date())) / 1000);

                    if (left < 0) {
                        left = 0;
                    }

                    // Number of days left
                    d = Math.floor(left / days);
                    updateDuo(0, 1, d);
                    left -= d * days;

                    // Number of hours left
                    h = Math.floor(left / hours);
                    updateDuo(2, 3, h);
                    left -= h * hours;

                    // Number of minutes left
                    m = Math.floor(left / minutes);
                    updateDuo(4, 5, m);
                    left -= m * minutes;

                    // Number of seconds left
                    s = left;
                    updateDuo(6, 7, s);

                    // Calling an optional user supplied callback
                    options.callback(d, h, m, s);

                    // Scheduling another call of this function in 1s
                    setTimeout(tick, 1000);
                })();

                // This function updates two digit positions at once
                function updateDuo(minor, major, value) {
                    switchDigit(positions.eq(minor), Math.floor(value / 10) % 10);
                    switchDigit(positions.eq(major), value % 10);
                }

                return this;
            };


            function init(elem, options) {
                elem.addClass('countdownHolder');

                // Creating the markup inside the container
                $.each(['Days', 'Hours', 'Minutes', 'Seconds'], function(i) {
                    $('<span class="count' + this + '">').html(
                        '<span class="position">\
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    					<span class="digit static">0</span>\
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    				</span>\
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    				<span class="position">\
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    					<span class="digit static">0</span>\
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    				</span>'
                    ).appendTo(elem);

                    if (this != "Seconds") {
                        elem.append('<span class="countDiv countDiv' + i + '"></span>');
                    }
                });

            }

            // Creates an animated transition between the two numbers
            function switchDigit(position, number) {

                var digit = position.find('.digit')

                if (digit.is(':animated')) {
                    return false;
                }

                if (position.data('digit') == number) {
                    // We are already showing this number
                    return false;
                }

                position.data('digit', number);

                var replacement = $('<span>', {
                    'class': 'digit',
                    css: {
                        top: '-2.1em',
                        opacity: 0
                    },
                    html: number
                });

                // The .static class is added when the animation
                // completes. This makes it run smoother.

                digit
                    .before(replacement)
                    .removeClass('static')
                    .animate({
                        top: '2.5em',
                        opacity: 0
                    }, 'fast', function() {
                        digit.remove();
                    })

                replacement
                    .delay(100)
                    .animate({
                        top: 0,
                        opacity: 1
                    }, 'fast', function() {
                        replacement.addClass('static');
                    });
            }
        })(jQuery);
    </script>
    <script>
        $(function() {

            var note = $('#note'),
                ts = new Date(2012, 0, 1),
                newYear = true;

            if ((new Date()) > ts) {
                // The new year is here! Count towards something else.
                // Notice the *1000 at the end - time must be in milliseconds
                @php
                    $days = 365;
                    $join_date = \Carbon\Carbon::parse(@$created_at);
                    $now = \Carbon\Carbon::now();
                    $diff = $join_date->diffInSeconds($now);
                    $seconds = $days * 86400 - $diff;
                    $diff_day = $days - $join_date->diffInDays($now);
                @endphp

                ts = (new Date()).getTime() + {{ $seconds }} * 1000;
                newYear = false;
            }

            $('#countdown').countdown({
                timestamp: ts,
                callback: function(days, hours, minutes, seconds) {

                    var message = "";

                    message += days + " day" + (days == 1 ? '' : 's') + ", ";
                    message += hours + " hour" + (hours == 1 ? '' : 's') + ", ";
                    message += minutes + " minute" + (minutes == 1 ? '' : 's') + " and ";
                    message += seconds + " second" + (seconds == 1 ? '' : 's') + " <br />";

                    if (newYear) {
                        message += "Congratulations! You can Withdraw Now";
                    } else {
                        message +=
                            "left to active passive wallet";
                    }

                    note.html(message);
                }
            });

        });
    </script>
@endpush

@push('style')
    <style>
        .countdownHolder {
            width: 100%;
            font: 18px/1.5 'Open Sans Condensed', sans-serif;
            letter-spacing: -3px;
        }

        .position {
            display: inline-block;
            height: 1.6em;
            overflow: hidden;
            position: relative;
            width: 1.05em;
        }

        .digit {
            position: absolute;
            display: block;
            width: 1em;
            background-color: #444;
            border-radius: 0.2em;
            text-align: center;
            color: #fff;
            letter-spacing: -1px;
        }

        .digit.static {
            box-shadow: 1px 1px 1px rgba(4, 4, 4, 0.35);

            background-image: linear-gradient(bottom, #3A3A3A 50%, #444444 50%);
            background-image: -o-linear-gradient(bottom, #3A3A3A 50%, #444444 50%);
            background-image: -moz-linear-gradient(bottom, #3A3A3A 50%, #444444 50%);
            background-image: -webkit-linear-gradient(bottom, #3A3A3A 50%, #444444 50%);
            background-image: -ms-linear-gradient(bottom, #3A3A3A 50%, #444444 50%);

            background-image: -webkit-gradient(linear,
                    left bottom,
                    left top,
                    color-stop(0.5, #3A3A3A),
                    color-stop(0.5, #444444));
        }

        /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     * You can use these classes to hide parts
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     * of the countdown that you don't need.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     */

        .countDays {
            /* display:none !important;*/
        }

        .countDiv0 {
            /* display:none !important;*/
        }

        .countHours {}

        .countDiv1 {}

        .countMinutes {}

        .countDiv2 {}

        .countSeconds {}


        .countDiv {
            display: inline-block;
            width: 16px;
            height: 1.6em;
            position: relative;
        }

        .countDiv:before,
        .countDiv:after {
            position: absolute;
            width: 5px;
            height: 5px;
            background-color: #444;
            border-radius: 50%;
            left: 50%;
            margin-left: -3px;
            top: 0.5em;
            box-shadow: 1px 1px 1px rgba(4, 4, 4, 0.5);
            content: '';
        }

        .countDiv:after {
            top: 0.9em;
        }
        
        .animate-charcter
        {
           text-transform: uppercase;
          background-image: linear-gradient(
            -225deg,
            #231557 0%,
            #44107a 29%,
            #ff1361 67%,
            #fff800 100%
          );
          background-size: auto auto;
          background-clip: border-box;
          background-size: 200% auto;
          color: #fff;
          background-clip: text;
          text-fill-color: transparent;
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          animation: textclip 2s linear infinite;
          display: inline-block;
              font-size: 30px;
        }
        
        @keyframes textclip {
          to {
            background-position: 200% center;
          }
        }
    </style>
@endpush
