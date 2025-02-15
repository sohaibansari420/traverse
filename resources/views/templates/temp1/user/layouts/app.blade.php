@extends($activeTemplate . 'user.layouts.master')

@section('content')
<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper" style="background:rgb(255, 255, 255);" class="wallet-open active">

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
            <p>Copyright © Designed &amp; Developed by <a href="#">Stealth Trade Bot</a> 2025</p>
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
