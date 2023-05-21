@extends('admin.layouts.app')

@push('style')
<style>
    .modal .fade {
        z-index: 10000000 !important;
    }
    .datepicker-here {z-index: 20000000 !important}
</style>
@endpush

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th scope="col">@lang('Sl')</th>
                            <th scope="col">@lang('Type')</th>
                            <th scope="col">@lang('Title')</th>
                            <th scope="col">@lang('Detail')</th>
                            <th scope="col">@lang('Till Date')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($notifications as $key => $notification)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>
                                    @if ($notification->type == 0)
                                        <span class="badge badge-primary">Notifications</span>
                                    @elseif ($notification->type == 1)
                                        <span class="badge badge-dark">Popup Notifications</span>
                                    @endif
                                </td>
                                <td> {{ $notification->title }} </td>
                                <td> {{ $notification->detail }} </td>
                                <td> {{ $notification->till_date }} </td>
                                <td>
                                    <a href="{{ route('admin.setting.notice.delete', $notification->id) }}" class="btn btn-danger" role="button">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            <div class="card-footer py-4">
                {{ $notifications->links('admin.partials.paginate') }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('admin.setting.notice.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('All user notice')</label>
                                <textarea rows="10" class="form-control nicEdit"  name="notice">{{ __($general->notice) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>@lang('Free user notice')</label>
                                <textarea rows="10" class="form-control nicEdit"  name="free_user_notice">{{ __($general->free_user_notice) }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer py-4">
                    <button type="submit" class="btn btn-block btn--primary mr-2">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--    edit modal--}}

    <div id="add-plan" class="modal  fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Notice')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.setting.notice.store')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-row">
                            <div class="form-group col-md-6 offset-md-3">
                                <label for="image-upload">Upload Image</label>
                                <img id='img-upload' src="{{asset('assets/images/default.png')}}" alt="Notification Image"/>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse… <input type="file" name="image" id="imgInp">
                                        </span>
                                    </span>
                                    <input id="image-upload" type="text" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="type" class="col-form-label">Type</label>
                                <select name="type" class="form-control" id="type">
                                    <option value="0">Notification</option>
                                    <option value="1">Popup Notification</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="show_type" class="col-form-label">Show Type</label>
                                <select name="show_type" class="form-control" id="show_type">
                                    <option value="0">All</option>
                                    <option value="1">User</option>
                                    <option value="2">Country</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="title" class="col-form-label">Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Title" id="title">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="detail" class="col-form-label">Detail</label>
                                <input type="text" class="form-control" name="detail" placeholder="detail" id="detail">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="username" class="col-form-label">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="User Name" id="username">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="country" class="col-form-label">Country</label>
                                <input type="text" class="form-control" name="country" placeholder="Country" id="country">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="birthdatepicker" class="col-form-label">Till Date</label>
                                <input style="position: relative; z-index: 10000 !important;" type="text" name="till_date" class="form-control datepicker-here" id="birthdatepicker" value="04/30/2021">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-block btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--success add-plan"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>

@endpush

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('.add-plan').on('click', function () {
                var modal = $('#add-plan');
                modal.modal('show');
            });
        })(jQuery);
    </script>
    <script>
        $(document).ready( function() {
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }

            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function(){
                readURL(this);
            });
        });
    </script>
@endpush