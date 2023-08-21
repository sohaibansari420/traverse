@extends($activeTemplate . 'user.layouts.master')

@section('content')
<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper" class="wallet-open active">

    @include($activeTemplate . 'user.partials.topnav')
    
    @include($activeTemplate . 'user.partials.sidenav')
    
    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    @yield('panel')
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->
    
    <!--**********************************
        Footer start
    ***********************************-->
    <div class="footer out-footer style-1">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="#">Millionaire Multiverse</a> 2023</p>
        </div>
    </div>

    <!--**********************************
        Footer end
    ***********************************-->


</div>
<!--**********************************
    Main wrapper end
***********************************-->
@endsection
