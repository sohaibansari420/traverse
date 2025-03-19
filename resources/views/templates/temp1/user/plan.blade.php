@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')
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
                    <div class="row">
                        @foreach ($plans as $data)
                            <div class="col-md-4 col-sm-6 mt-3">
                                <div class="pricingTable">
                                    <div class="pricingTable-header">
                                        <h3 class="title">{{$data->name}}</h3>
                                        <div class="price-value">
                                            <span class="amount">${{getAmount($data->price)}}</span>
                                        </div>
                                    </div>
                                    <ul class="pricing-content">
                                        @if (@unserialize($data->features))
                                            @foreach (@unserialize($data->features) as $feature)
                                                <li>{{ $feature }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="pricingTable-signup">
                                        <button data-bs-target="#confBuyModal{{ $data->id }}"
                                                data-bs-toggle="modal">Buy Now</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal -->
                            <div class="modal" id="confBuyModal{{ $data->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="confBuyModal{{ $data->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confBuyModal{{ $data->id }}">
                                                @lang('Confirm Purchase ' . $data->name)?
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            </button>
                                        </div>
                                        <form method="post" action="{{ route('user.plan.purchase') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <p class="text-center">{{ getAmount($data->price) }}
                                                    {{ $general->cur_text }} @lang('will subtract from your balance')</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="plan_id" value="{{ $data->id }}"
                                                    class="btn btn-primary float-end">
                                                    @lang('Confirm')</button>
                                                {{-- <button type="button" class="btn btn-success" data-bs-target="#confUpgradeModal{{ $data->id }}"
                                                    data-bs-toggle="modal">Upgrade Plan</button> --}}
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal" id="confUpgradeModal{{ $data->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="confUpgradeModal{{ $data->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confBuyModal{{ $data->id }}">
                                                @lang('Confirm Purchase ' . $data->name)?
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            </button>
                                        </div>
                                        <form method="post" action="{{ route('user.plan.upgrade.purchase') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <table class="table">
                                                    <thead>
                                                      <tr>
                                                        <th scope="col">id</th>
                                                        <th scope="col">Plan</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Plans</th>
                                                        <th scope="col">Upgrade</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($myPlans as $key => $plan)
                                                            @if (number_format($plan->amount,2,'.',"") ==  number_format($data->price,2,'.',""))
                                                                <tr>
                                                                    <th scope="row">{{$plan->id}}</th>
                                                                    <td>{{$data->name}}</td>
                                                                    <td>{{$plan->amount}}</td>
                                                                    <td>
                                                                        <select name="plans_select[]">
                                                                            @foreach ($plans as $plan_v)
                                                                                @if($plan_v->id > $data->id)
                                                                                    <option value="{{$plan_v->id}}-{{$plan->id}}">{{$plan_v->name}}</option>
                                                                                @endif
                                                                            @endforeach    
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="radio" name="upgrade" value="{{$plan->id}}"/></td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                  </table>
                                                <p class="text-center">{{ getAmount($data->price) }}
                                                    {{ $general->cur_text }} @lang('will subtract from your balance')</p>
                                                    <input type="hidden" name="plan_amount" value="{{$data->price}}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                                    @php
                                                        if(in_array($data->price ,$myPlansAmounts)) {
                                                            $plan_found=true;
                                                        }
                                                        else{
                                                            $plan_found=false;
                                                        }
                                                    @endphp
                                                @if($data->id != $planCount && $plan_found==true)
                                                    <button type="submit" name="plan_upgrade_id" value="{{ $data->id }}"
                                                        class="btn btn-primary float-end">
                                                        @lang('Upgrade Plan')</button>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.epin').hide();
        $(document).ready(function() {
            $('.type').on('change', function() {
                var type = $(this).find(":selected").val();
                if (type == 2) {
                    $('.epin').show();
                } else {
                    $('.epin').hide();
                }
            });
        });
    </script>
@endpush

@push('style-lib')
    <!-- Pricing Table CSS -->
    <style>
        .pricingTable{
        color:#fff;
        font-family: 'Open Sans', sans-serif;
        text-align: center;
        padding: 0 0 35px;
        border: 8px solid #17a2b8;
        border-radius: 30px;
    }
    .pricingTable .pricingTable-header{
        padding: 30px 0 0;
        margin: 0 auto 30px;
    }
    .pricingTable .title{
        font-size: 25px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        width: 85%;
        padding: 10px 0;
        margin: 0 auto 25px;
        border-radius: 15px;
        border: 3px solid #17a2b8;
    }
    .pricingTable .price-value .amount{
        font-size: 50px;
        font-weight: 800;
        line-height: 50px;
        display: inline-block;
    }
    .pricingTable .price-value .duration{
        font-size: 16px;
        font-weight: 500;
        display: block;
        text-transform: lowercase;
    }
    .pricingTable .pricing-content{
        text-align: left;
        padding: 0;
        margin: 0 0 30px;
        list-style: none;
        display: inline-block;
    }
    .pricingTable .pricing-content li{
        font-size: 17px;
        line-height: 25px;
        text-transform: capitalize;
        border-bottom: 1px solid #fff;
        padding: 0 0 13px 25px;
        margin: 0 0 12px;
        position: relative;
    }
    .pricingTable .pricing-content li:last-child{
        margin-bottom: 0;
        border-bottom: none;
    }
    .pricingTable .pricing-content li:before{
        content: "\f00c";
        color: #17a2b8;
        font-family: "Font Awesome 5 free";
        font-size: 16px;
        font-weight: 900;
        position: absolute;
        top: 1px;
        left: 0;
    }
    .pricingTable .pricingTable-signup button{
        color: #fff;
        background:#17a2b8;
        font-size: 15px;
        font-weight: 600;
        line-height: 30px;
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0,0,0,0.8);
        padding: 10px 30px;
        border: 2px solid transparent;
        border-radius: 10px;
        transition: all 0.3s ease 0s;
    }
    /* .pricingTable .pricingTable-signup button:hover{
        color:#17a2b8;
        background: transparent;
        text-shadow: 3px 3px 3px rgba(255, 255, 255, 0.3);
        border: 3px solid #17a2b8;
    } */
    @media only screen and (max-width: 990px){
        .pricingTable{ margin: 0 0 40px; }
    }
    </style>
@endpush