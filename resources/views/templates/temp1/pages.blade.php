@extends($activeTemplate.'layouts.master')

@section('content')
    @include($activeTemplate.'layouts.breadcrumb')

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
