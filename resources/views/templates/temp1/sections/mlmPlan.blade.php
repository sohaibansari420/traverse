@php

    $planTitle = getContent('mlmPlan.content', true);
    $plans = \App\Models\Plan::where('status', 1)->get();

@endphp


<section class="pricing-section padding-bottom padding-top">
    <div class="container">
        <div class="section-header">
            <h2 class="title">@lang(@$planTitle->data_values->heading)</h2>
            <p>@lang(@$planTitle->data_values->sub_heading)</p>
        </div>

        <div class="row justify-content-center mb-30-none">
            @foreach($plans  as $plan)
            <div class="col-lg-4 col-md-6 col-sm-10 mb-30">
                <div class="plan-card text-center bg_img" data-background="{{asset(getImage('assets/images/frontend/mlmPlan/' . @$planTitle->data_values->background_image))}}">
                    <h4 class="plan-card__title mb-2">{{__(@$plan->name)}}</h4>
                    <div class="price-range text-white mt-5"> {{getAmount($plan->price)}}  {{$general->cur_text}} </div>
                    <ul class="plan-card__features mt-4">
                     <li>   @lang('Business Volume') (@lang('BV')) : <span class="amount">{{$plan->bv}}</span>
                         <span class="icon float-right" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-question-circle"></i></span></li>
                        <li>
                            @lang('Referral Commission') : <span class="amount">{{$general->cur_sym}} {{getAmount($plan->ref_com)}}</span>
                            <span class="icon float-right" data-toggle="modal" data-target="#exampleModal2" ><i class="fas fa-question-circle"></i></span>
                        </li>

                        <li>
                            @lang('Commission To Tree') : <span class="amount">{{$general->cur_sym}} {{getAmount($plan->tree_com)}}</span>
                            <span class="icon float-right" data-toggle="modal" data-target="#exampleModal3"><i class="fas fa-question-circle"></i></span>
                        </li>
                    </ul>

                    <a href="{{route('user.plan.index')}}" class="custom-button theme text-white mt-3">@lang('Subscribe now')</a>
                </div>
            </div>

            @endforeach

        </div>

    </div>
</section>





<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang("Business Volume (BV) info")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>   <span class="text-danger">@lang('When someone from your below tree subscribe this plan, You will get this Business Volume  which will be used for matching bonus').</span>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Referral Commission info')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>  <span class=" text-danger">@lang('When Your Direct-Referred/Sponsored  User Subscribe in') <b> @lang('ANY PLAN') </b>, @lang('You will get this amount').</span>
                    <br>
                    <br>
                    <span class="text-success"> @lang('This is the reason You should Choose a Plan With Bigger Referral Commission').</span> </h5>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Commission to tree info')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class=" text-danger">@lang('When someone from your below tree subscribe this plan, You will get this amount as Tree Commission'). </h5>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>


