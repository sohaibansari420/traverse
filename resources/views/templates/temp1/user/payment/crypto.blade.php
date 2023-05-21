@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-deposit text-center">
                <div class="card-body card-body-deposit text-center">
                    <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text-success"> {{ $data->amount }}</span>
                        {{ $data->currency }}</h4>
                    <h5 class="mb-2">@lang('TO') <span class="text-success"> {{ $data->sendto }}</span></h5>
                    <img src="{{ $data->img }}" alt="@lang('image')" width="100" height="100">
                    <h4 class="bold my-4">@lang('SCAN TO SEND')</h4>
                    <hr>
                    <h5 class="text-success font-weight-bold">@lang('Your Account will be credited automatically after 3 network confirmations. ')</h5>
                    <h5 class="text-danger font-weight-bold">@lang('Kindly pay $2 extra if you are depositing less then $200 to avoid delays by third party. ')</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
