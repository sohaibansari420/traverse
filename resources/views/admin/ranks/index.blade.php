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
                                    <th scope="col">@lang('Points')</th>
                                    <th scope="col">@lang('Reward')</th>
                                    <th scope="col">@lang('Value')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ranks as $key => $rank)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Name')">{{ __($rank->name) }}</td>
                                        <td data-label="@lang('Points')">{{ getAmount($rank->points) }}</td>
                                        <td data-label="@lang('Reward')">{{ __($rank->reward) }}</td>
                                        <td data-label="@lang('Value')">
                                            {{ $general->cur_sym }}{{ getAmount($rank->value) }}</td>


                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $rank->id }}" data-name="{{ $rank->name }}"
                                                data-points="{{ $rank->points }}" data-reward="{{ $rank->reward }}"
                                                data-value="{{ $rank->value }}" data-image="{{ $rank->image }}"
                                                data-reward_image="{{ $rank->reward_image }}" data-original-title="Edit">
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
                    {{ $ranks->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div id="edit-rank" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Rank')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.rank.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control rank_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control name" name="name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Points') :</label>
                                <input class="form-control points" name="points" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Reward') :</label>
                                <input class="form-control reward" name="reward" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Value') :</label>
                                <input class="form-control value" name="value" required>
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
                                            <input type="file" class="profilePicUpload" id="profilePicUpload1"
                                                accept=".png, .jpg, .jpeg, .svg, .gif" name="image">
                                            <label for="profilePicUpload1" class="bg-primary">@lang('Select Image') </label>
                                        </div>
                                    </div>
                                </div>
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
                                                accept=".png, .jpg, .jpeg, .svg, .gif" name="reward_image">
                                            <label for="profilePicUpload2" class="bg-primary">@lang('Select Reward Image') </label>
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

    <div id="add-rank" class="modal  fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Rank')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.rank.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control rank_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control" name="name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Points') :</label>
                                <input class="form-control" name="points" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Reward') :</label>
                                <input class="form-control" name="reward" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Value') :</label>
                                <input class="form-control" name="value" required>
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
                                            <label for="profilePicUpload2" class="bg-primary">@lang('Select Reward Image') </label>
                                        </div>
                                    </div>
                                </div>
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
                                                accept=".png, .jpg, .jpeg, .svg, .gif" name="reward_image">
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
    <a href="javascript:void(0)" class="btn btn-sm btn--success add-rank"><i
            class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-rank');
                modal.find('.name').val($(this).data('name'));
                modal.find('.points').val($(this).data('points'));
                modal.find('.reward').val($(this).data('reward'));
                modal.find('.value').val($(this).data('value'));
                modal.find('.image').val($(this).data('image'));
                modal.find('.reward_image').val($(this).data('reward_image'));

                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.add-rank').on('click', function() {
                var modal = $('#add-rank');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
