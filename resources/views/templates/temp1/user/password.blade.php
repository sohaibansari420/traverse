@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-white">@lang('Reset Your Password')</h3>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <form action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <i class="fe fe-unlock"></i>
                                        </span>
                                        <input type="password" class="form-control" placeholder="Current Password"
                                            name="current_password" required minlength="5">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <i class="fe fe-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" placeholder="New Password"
                                            name="password" required minlength="5">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <i class="fe fe-feather"></i>
                                        </span>
                                        <input type="password" class="form-control" placeholder="Confirm Password"
                                            name="password_confirmation" required minlength="5">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row justify-content-center mt-4">
                                        <div class="col-8">
                                            <button type="submit"
                                                class="btn btn-primary btn-block btn-lg">@lang('Change Password')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('user.profile-setting') }}" class="btn btn-success btn-shadow"><i
            class="fa fa-user"></i>@lang('Profile Setting')</a>
@endpush
