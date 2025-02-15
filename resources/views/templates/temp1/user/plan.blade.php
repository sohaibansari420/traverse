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
                            <div class="col-md-12 col-sm-12 mt-3">
                                <div class="pricingTable">
                                    <div class="pricingTable-header">
                                        <h3 class="title">{{$data}}</h3>
                                    </div>
                                    <div class="pricingTable-signup">
                                        <a href="{{ route('user.plan.details', ['title' => $data]) }}" class="btn btn-lg btn-primary">Details</a>
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
        font-size: 35px;
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
        font-size: 25px;
        font-weight: 600;
        line-height: 45px;
        text-transform: uppercase;
        text-shadow: 0 0 10px rgba(0,0,0,0.8);
        padding: 10px 30px;
        border: 3px solid transparent;
        border-radius: 10px;
        transition: all 0.3s ease 0s;
    }
    .pricingTable .pricingTable-signup button:hover{
        color:#17a2b8;
        background: transparent;
        text-shadow: 3px 3px 3px rgba(255, 255, 255, 0.3);
        border: 3px solid #17a2b8;
    }
    @media only screen and (max-width: 990px){
        .pricingTable{ margin: 0 0 40px; }
    }
    </style>
@endpush