@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <form class="contact-form" method="POST" action="{{ route('user.balance.transfer.post') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="InputMail">
                                        <h5>@lang('Select Wallet')<span class="requred">*</span> </h5>
                                    </label>
                                    <select id="wallet_id" class="form-control form-control-lg" name="wallet_id">
                                        <option selected disabled>Select Wallet</option>
                                        @foreach ($wallets as $wallet)
                                            @if ($wallet->wallet->display &&
                                                $wallet->wallet->id != 3 &&
                                                $wallet->wallet->id != 5 &&
                                                $wallet->wallet->id != 9)
                                                <option value="{{ $wallet->wallet->id }}">{{ $wallet->wallet->name }} -
                                                    {{ getAmount($wallet->balance) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="InputMail">
                                        <h5>@lang('Transfer Amount')<span class="requred">*</span> </h5>
                                    </label>
                                    <input onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        class="form-control form-control-lg" autocomplete="off" id="amount"
                                        name="amount" placeholder="@lang('Amount') {{ __($general->cur_text) }}"
                                        required>
                                    <div id="balance-message"></div>
                                </div>
                                <div class="form-group col-md-12 text-center">
                                    <p>{{ getAmount($general->bal_trans_fixed_charge) }}% Transfer charges apply</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class=" btn btn-block btn-primary mr-2">@lang('Transfer Balance')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- section-wrapper -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('keyup', '#amount', function() {
                var inputAmount = parseFloat($('#amount').val());
                var token = "{{ csrf_token() }}";

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.get.total.charge') }}",
                    data: {
                        'amount': inputAmount,
                        '_token': token
                    },
                    success: function(data) {
                        $("#balance-message").html(data);

                    }
                });

                $('#amount').keyup(function(event) {
                    var regex = /[0-9]|\./;
                    var text = $('#amount').val();

                    if (!(regex.test(text))) {
                        $("#balance-message").html(
                            "<span style='color: red'>Invalid Amount</span>");
                    }
                });
            });
        });
    </script>
@endpush
