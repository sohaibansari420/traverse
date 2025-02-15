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
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Detail')</th>
                                <th scope="col">@lang('Date(Start/End)')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($promotions as $promotion)
                                <tr>
                                    <td data-label="@lang('Name')">
                                        @if($promotion->name)
                                            {{ $promotion->name }}
                                        @else
                                            @lang('Unknown')
                                        @endif
                                    </td>
                                    <td data-label="@lang('Detail')">{{ $promotion->detail }}</td>
                                    <td data-label="@lang('Date')">{{ \Carbon\Carbon::parse($promotion->start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($promotion->end)->format('d M Y') }}</td>
                                    <td data-label="@lang('Status')">{{ ($promotion->status == '0') ? 'Not-Active' : 'Active' }}</td>
                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)"
                                           data-id="{{ $promotion->id }}"
                                           class="icon-btn btn--danger ml-1 removeModalBtn" data-toggle="tooltip"
                                           data-original-title="@lang('Remove')">
                                            <i class="las la-trash"></i>
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
            </div><!-- card end -->
        </div>
    </div>


    <div id="add_promotion" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Are you sure want to Add?')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.save.promotion') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name" class="col-form-label">Name:</label>
                                <input type="text" class="form-control" name="name" placeholder="Name" id="name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="detail" class="col-form-label">Detail:</label>
                                <textarea type="text" cols="12" rows="3" class="form-control" name="detail" placeholder="Detail" id="detail"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="start_date" class="col-form-label">Start Date:</label>
                                <input name="start_date" type="date"
                                    data-language="en" class="form-control bg-white text--black"
                                    data-position='bottom right' placeholder="@lang('Min Date')" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="end_date" class="col-form-label">End Date:</label>
                                <input name="end_date" type="date"
                                    data-language="en" class=" form-control bg-white text--black"
                                    data-position='bottom right' placeholder="@lang('Max date')" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="promotion_for" class="col-form-label">Is this promotion for?</label>
                                <select class="form-control" name="promotion_for" id="promotion_for">
                                    <option value="user">Users</option>
                                    <option value="country">Countries</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row d-none user_id">
                            <div class="form-group col-md-12">
                                <label for="user_id" class="col-form-label">Users:</label>
                                <select class="form-control" name="user_id[]" id="user_id" multiple>
                                    <option value="all">All</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->firstname }}-{{$user->lastname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row d-none country">
                            <div class="form-group col-md-12">
                                <label for="country" class="col-form-label">Country:</label>
                                <select name="country[]" class="form-control" style="" id="country" multiple>
                                    <option value="all">All</option>
                                    @include('partials.country')
                                </select>
                            </div>
                        </div>
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
                            <div class="form-group col-md-3 offset-md-9">
                                <label class="font-weight-bold">@lang('Status')</label>
                                <input  type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="status" checked>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Remove Subscriber MODAL --}}
    <div id="removeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Are you sure want to remove?')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.delete.promotion') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="subscriber">
                        <p><span class="font-weight-bold subscriber-email"></span> @lang('will be removed.')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Remove')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn--primary box--shadow1 text--small text-white" onclick="AddPromotion()" ><i class="fa fa-fw fa-paper-plane"></i>@lang('Add Promotion')</a>
@endpush
@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('.removeModalBtn').on('click', function() {
                $('#removeModal').find('input[name=subscriber]').val($(this).data('id'));
                $('#removeModal').find('.subscriber-email').text($(this).data('email'));
                $('#removeModal').modal('show');
            });
            $('[name="promotion_for"]').on('change',function(){
                var val=$(this).val();
                if (val == 'user') {
                    $('.user_id').removeClass('d-none');
                    $('.country').addClass('d-none');
                }
                else{
                    $('.country').removeClass('d-none');
                    $('.user_id').addClass('d-none');
                }
            });
            $('.datepicker-here').datepicker({
                autoclose: true,
            });
            @if (@$country)
                $(`option[data-country={{ $country }}]`).attr('selected', '');
            @endif
        })(jQuery)

        function AddPromotion(){
            $('#add_promotion').modal('show');
            $('#country').select2();
            $('#user_id').select2();
            $('[name="promotion_for"]').trigger('change');
        }
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
    </script>
@endpush
