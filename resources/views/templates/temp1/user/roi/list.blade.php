@extends($activeTemplate . 'user.layouts.app')

@section('panel')
<style>
    .trade-box {
        background: #101010;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .btn-custom {
        background-color: #28a745;
        color: white;
        width: 100%;
    }
    .input-group-text {
        background: #d4af37;
        color: white;
    }
</style>
    @include($activeTemplate . 'user.partials.breadcrumb')
    <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body bg-white" style="border: 1px solid black;">
                    <div class="row">
                        <div class="form-group col-md-12 mb-4">
                            <label class="font-weight-bold">@lang('Packages')</label>
                            <select class="form-control wallet_id" name="plan_id" id="package_id">
                                <option value="">Select your plan</option>
                                @foreach ($myPLans as $data)
                                    <option value="{{ $data->id }}">{{ trans($data->plan->name) }}</option>
                                @endforeach
                            </select>
                        </div>  
                        {{-- <div class="form-group col-md-5">
                            <label class="font-weight-bold"> @lang('Amount') :</label>
                            <input class="form-control" name="amount" value="">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="font-weight-bold"> @lang('Range') :</label>
                            <input class="form-control" name="range" value="">
                        </div>
                        <div class="form-group col-md-2 mt-4">
                            <button class="btn btn-block btn-dark excute_roi_range">@lang('Execute')</button>
                        </div>    --}}
                    </div>
                    <div class="col-xl-12">
                    <div class="text-center mb-3">
                        {{-- <span class="badge bg-secondary fs-5">Current Balance: $10,000.00</span> --}}
                        <h2 class="text-center badge bg-secondary fs-5 text-black">AUTOMATIC OPERATIONS</h2>
                    </div>
                    <form id="formROI" method="post" action="{{ route('user.roi.compound') }}">
                        @csrf
                        <input type="hidden" name="trx" id="trx">
                        <div class="row">
                            <!-- Buy Section -->
                            <div class="col-md-6">
                                <div class="trade-box text-center">
                                    <h4 class="text-center text-success">Buy <i class="fa-solid fa-cart-shopping"></i></h4>
                                    <p><strong>COIN:</strong> USDT</p>
                                    <p><strong>ACQUISITIONS:</strong> USDT</p>
                                    <p><strong>BROKER:</strong> BitcoinToYou</p>
                                    <p><strong>VALUE:</strong> $95,623</p>
                
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Amount of Value</strong></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" name="plan_price" id="plan_price" class="form-control" readonly placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Sell Section -->
                            <div class="col-md-6">
                                <div class="trade-box text-center">
                                    <h4 class="text-center text-danger"><i class="fa-solid fa-cart-shopping"></i> Sell</h4>
                                    <p><strong>COIN:</strong> USDT</p>
                                    <p><strong>ACQUISITIONS:</strong> USDT</p>
                                    <p><strong>BROKER:</strong> Bitrecife</p>
                                    <p><strong>VALUE:</strong> $96,376</p>
                
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Estimated Profit</strong></label>
                                        <div class="input-group">
                                            <span class="input-group-text">%</span>
                                            <input type="text" class="form-control" name="plan_purchase" id="plan_purchase" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <div class="text-center mt-3">
                            <button type="submit" name="compounding" id="compounding" value="1" class="btn btn-success w-100">Execute</button>
                        </div>
                        
                    </form>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Detail')</th>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Percentage')</th>
                                    <th scope="col">@lang('ROI')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $trans)
                                    <tr>
                                        <td>{{ $trans->details }}</td>
                                        <td>{{ \Carbon\Carbon::parse($trans->created_at)->format('d M Y') }}</td>
                                        <td>{{ $trans->amount }}</td>
                                        <td>{{ $trans->roi_percent }}</td>
                                        <td>{{ ($trans->amount * $trans->roi_percent) / 100 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
@endsection

@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
    <script>
        $(document).ready(function(){
            $('#compounding').prop('disabled', true);
            $('#package_id').on('change',function(){
                $('#compounding').prop('disabled', false);
                var today = new Date(); // Get current date
                var day = today.getDay(); // Get day of the week (0 = Sunday, 6 = Saturday)
                
                var selectedVal = $(this).val();
                $('#plan_price').val('');
                $('#plan_purchase').val('');
                $('#trx').val('');
                if (selectedVal !== "") {
                    $.ajax({
                        url: "{{ route('user.plan.roi.details') }}",
                        type: 'GET',
                        data: {
                            _token: "{{ csrf_token() }}",
                            planId : selectedVal,
                        },
                        success: function(data) {
                            console.log(data);
                            $('#plan_price').val(data.price);
                            if (data.plan_roi == 1) {
                                $('#plan_purchase').val(data.percentage);
                            }
                            else{
                                $('#plan_purchase').val(0);
                                alert('Package is with only points,so you don"t have the access to roi operation.')
                            }
                            $('#trx').val(data.trx);
                            if ((day === 0 || day === 6) || (data.roi_status == 0 && data.plan_roi == 1 && data.planStartHours == 1)) {
                                $('#compounding').prop('disabled', false);
                            }else{
                                $('#compounding').prop('disabled', true);   
                            }
                        }
                    });
                }
                else{
                    alert('Please Select the package first');
                }
            });

            setInterval(function () {
                if ($('#package_id').val() !== "") {
                    $('#package_id').trigger('change'); // Manually trigger the event
                }
            }, 300000);
        });
    </script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush