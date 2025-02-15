@extends('admin.layouts.app')

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
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Title')</th>
                                    <th scope="col">@lang('Price')</th>
                                    <th scope="col">@lang('Business Volume (BV)')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $key => $plan)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Name')">{{ __($plan->name) }}</td>
                                        <td data-label="@lang('Title')">{{ __($plan->title) }}</td>
                                        <td data-label="@lang('Price')">{{ getAmount($plan->price) }}
                                            {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Bv')">{{ $plan->bv }}</td>

                                        <td data-label="@lang('Status')">
                                            @if ($plan->status == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $plan->id }}" data-name="{{ $plan->name }}"
                                                data-title="{{ $plan->title }}"
                                                data-description="{{ $plan->description }}"
                                                data-status="{{ $plan->status }}" data-bv="{{ $plan->bv }}"
                                                data-image="{{ $plan->image }}"
                                                data-features="{{ json_encode(unserialize($plan->features)) }}"
                                                data-price="{{ getAmount($plan->price) }}" data-original-title="Edit">
                                                <i class="la la-pencil"></i>
                                            </button>
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
                    {{ $plans->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div id="edit-plan" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Plan')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.plan.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control plan_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control name" name="name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Description') </label>
                                <input class="form-control description" name="description" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Price') </label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">{{ $general->cur_sym }}
                                        </span></div>
                                    <input type="text" class="form-control  price" name="price" required>
                                </div>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Title')</label>
                                <input class="form-control title" name="title" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Business Volume (BV)')</label>
                                <input class="form-control bv" name="bv" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Features')</label>
                                <input class="form-control features" name="features" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Status')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="status" checked>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-12">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="profilePicPreview logoPicPrev logoPrev"
                                                        style="background-image: url({{ getImage(imagePath()['logoIcon']['path'] . '/logo.png', '?' . time()) }})">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" id="profilePicUpload2"
                                                accept=".png, .jpg, .jpeg, .svg, .gif" name="image">
                                            <label for="profilePicUpload2" class="bg-primary">@lang('Select Image') </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-block btn--primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-plan" class="modal  fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New plan')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.plan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control plan_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control" name="name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Description') </label>
                                <input class="form-control" name="description" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Price') </label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span
                                            class="input-group-text">{{ $general->cur_sym }} </span></div>
                                    <input type="text" class="form-control" name="price" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Title')</label>
                                <input class="form-control" name="title" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Business Volume (BV)')</label>
                                <input class="form-control" name="bv" required>
                            </div>
                        </div>

                        <div id="items-container" class="form-row">
                            <div class="form-group col-md-4">
                                <label for="features" class="col-form-label">Feature <ins class="ind">1</ins></label>
                                <div class="input-group">
                                    <input name="features[]" type="text" class="form-control" placeholder="Features"
                                        id="features">
                                    <div class="input-group-append">
                                        <span class="btn btn-danger rmv"><i class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <button class="btn btn-primary btn-block" id="additem">Add Feature</button>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Status')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="status" checked>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-12">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="profilePicPreview logoPicPrev logoPrev"
                                                        style="background-image: url({{ getImage(imagePath()['logoIcon']['path'] . '/logo.png', '?' . time()) }})">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" id="profilePicUpload2"
                                                accept=".png, .jpg, .jpeg, .svg, .gif" name="image">
                                            <label for="profilePicUpload2" class="bg-primary">@lang('Select Image') </label>
                                        </div>
                                    </div>
                                </div>
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
    <a href="javascript:void(0)" class="btn btn-sm btn--success add-plan"><i
            class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-plan');
                modal.find('.title').val($(this).data('title'));
                modal.find('.name').val($(this).data('name'));
                modal.find('.description').val($(this).data('description'));
                modal.find('.price').val($(this).data('price'));
                modal.find('.bv').val($(this).data('bv'));
                modal.find('.image').val($(this).data('image'));
                modal.find('.features').val($(this).data('features'));
                modal.find('input[name=daily_ad_limit]').val($(this).data('daily_ad_limit'));

                if ($(this).data('status')) {
                    modal.find('.toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked', true);

                } else {
                    modal.find('.toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked', false);
                }

                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.add-plan').on('click', function() {
                var modal = $('#add-plan');
                modal.modal('show');
            });
        })(jQuery);
    </script>
    <script>
        (function($) {
            $(document).ready(function() {
                $('#additem').click(function(e) {
                    e.preventDefault();
                    var index = parseInt($('#items-container .form-group').last().find('.ind').text());
                    //console.log(index);
                    var html =
                        '<div class="form-group col-md-4"> <label for="features" class="col-form-label">Feature <ins class="ind">' +
                        (index + 1) +
                        '</ins></label> <div class="input-group"> <input name="features[]" type="text" class="form-control" placeholder="Features" id="features"> <div class="input-group-append"> <span class="btn btn-danger rmv"><i class="fa fa-trash"></i></span> </div> </div> </div>';
                    $('#items-container').append(html);
                });
                $('body').on('click', '.rmv', function() {
                    $(this).closest('.form-group').fadeOut('slow', function() {
                        $(this).remove();
                    });
                });
            });
        })(jQuery);
    </script>
@endpush
