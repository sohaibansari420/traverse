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

                                        {{-- <div class="col-11">
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
                                        </div> --}}
                                        <div class="form-group">
                                            <label>@lang('Enter Amount'):</label>
                                            <div class="input-group p-2">
                                                <input id="amount" type="text" class="form-control form-control-lg"
                                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                    name="amount" placeholder="0.00" required value="{{ old('amount') }}">
                                            </div>
                                        </div>
                                        <div class="mt-auto text-center">
                                            <button class="btn btn-outline-info rounded-pill px-4" onclick="sendPayment(this);">
                                                <i class="fas fa-shopping-cart me-2"></i>@lang('Deposit Now')
                                            </button>
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
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script>

        const ERC20_ABI = [
            {
            constant: false,
            name: "transfer",
            type: "function",
            inputs: [
                { name: "_to", type: "address" },
                { name: "_value", type: "uint256" }
            ],
            outputs: [{ name: "", type: "bool" }],
            }
        ];
           
        async function sendPayment(buttonElement) {
            const tokenAddress = "0x55d398326f99059fF775485246999027B3197955";
            const toWallet = '0x3D1207552Fd7Bf140795f8218137BC53406154b1';
            const tokenDecimals = 18;

            const amountInUSD = $("#amount").val();

            buttonElement.disabled = true;
            buttonElement.innerText = "Processing...";

            if(amountInUSD == null || amountInUSD == ""){
                alert("Enter the deposit amount to proceed!!!!");
                return ;
            } 

             if (!window.ethereum) {
                alert("Please install MetaMask");
                return;
            }

            if (!toWallet || !Web3.utils.isAddress(toWallet)) {
                alert("Please enter a valid recipient wallet address.");
                return;
            }
            

            const web3 = new Web3(window.ethereum);
            await window.ethereum.request({ method: 'eth_requestAccounts' });

            const accounts = await web3.eth.getAccounts();
            const from = accounts[0];
           
            const amount = (amountInUSD * Math.pow(10, tokenDecimals)).toString();
            console.log("Token Address:", tokenAddress);
            console.log("Recipient Address:", toWallet);
            
            const contract = new web3.eth.Contract(ERC20_ABI, tokenAddress);

            try {
                const tx = await contract.methods.transfer(toWallet, amount).send({ from });
                alert("Payment sent successfully!");
                // Notify backend
                $.post('/user/payment-success', {
                    user_wallet: from,
                    amount_usd: amountInUSD,
                    tx_hash: tx.transactionHash,
                    _token: '{{ csrf_token() }}'
                });
                buttonElement.disabled = false;
                buttonElement.innerText = "Deposit Now";
            } catch (err) {
                console.error(err);
                alert("Transaction failed");
            }
        }
    </script>
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
