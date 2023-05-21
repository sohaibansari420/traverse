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
                                    <th scope="col">@lang('Wallet')</th>
                                    <th scope="col">@lang('Released On')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissions as $key => $commission)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Name')">{{ __($commission->name) }}</td>
                                        <td data-label="@lang('Wallet')">{{ __($commission->wallet->name) }}</td>
                                        <td data-label="@lang('Released On')">{{ __(@$commission->commissionRelease->name) }}
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if ($commission->status == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $commission->id }}" data-name="{{ $commission->name }}"
                                                data-wallet_id="{{ $commission->wallet_id }}"
                                                data-commission_release_id="{{ $commission->commission_release_id }}"
                                                data-is_compounding="{{ $commission->is_compounding }}"
                                                data-is_package="{{ $commission->is_package }}"
                                                data-icon="{{ $commission->icon }}"
                                                data-status="{{ $commission->status }}" data-original-title="Edit">
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
                    {{ $commissions->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div id="edit-commission" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit commission')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.commission.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control commission_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control name" name="name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Icon') :</label>
                                <input class="form-control icon" name="icon" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Wallet')</label>
                                <select class="form-control wallet_id" name="wallet_id">
                                    @foreach ($wallets as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Released On')</label>
                                <select class="form-control commission_release_id" name="commission_release_id">
                                    @foreach ($releases as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-group col-md-4 is_package">
                                <label class="font-weight-bold">@lang('Package')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="is_package" checked>
                            </div>
                            <div class="form-group col-md-4 is_compounding">
                                <label class="font-weight-bold">@lang('Compounding')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="is_compounding" checked>
                            </div>
                            <div class="form-group col-md-4 status">
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
                                            <input type="file" class="profilePicUpload" id="profilePicUpload1"
                                                accept=".png, .jpg, .jpeg, .svg, .gif" name="image">
                                            <label for="profilePicUpload1" class="bg-primary">@lang('Select Image') </label>
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

    <div id="add-commission" class="modal  fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New commission')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.commission.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control commission_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control" name="name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Icon') :</label>
                                <input class="form-control" name="icon" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Wallet')</label>
                                <select class="form-control" name="wallet_id">
                                    @foreach ($wallets as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Released On')</label>
                                <select class="form-control" name="commission_release_id">
                                    @foreach ($releases as $data)
                                        <option value="{{ $data->id }}">{{ trans($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Package')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="is_package" checked>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Compounding')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="is_compounding" checked>
                            </div>
                            <div class="form-group col-md-4">
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
    <a href="javascript:void(0)" class="btn btn-sm btn--success add-commission"><i
            class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-commission');
                modal.find('.name').val($(this).data('name'));
                modal.find('.icon').val($(this).data('icon'));

                modal.find('.wallet_id option[value=' + $(this).data('wallet_id') + ']').attr('selected',
                    'selected');

                modal.find('.commission_release_id option[value=' + $(this).data('commission_release_id') + ']')
                    .attr(
                        'selected',
                        'selected');

                if ($(this).data('is_package')) {
                    modal.find('.is_package .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="is_package"]').prop('checked', true);

                } else {
                    modal.find('.is_package .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="is_package"]').prop('checked', false);
                }

                if ($(this).data('is_compounding')) {
                    modal.find('.is_compounding .toggle').removeClass('btn--danger off').addClass(
                        'btn--success');
                    modal.find('input[name="is_compounding"]').prop('checked', true);

                } else {
                    modal.find('.is_compounding .toggle').addClass('btn--danger off').removeClass(
                        'btn--success');
                    modal.find('input[name="is_compounding"]').prop('checked', false);
                }

                if ($(this).data('status')) {
                    modal.find('.status .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked', true);

                } else {
                    modal.find('.status .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked', false);
                }

                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.add-commission').on('click', function() {
                var modal = $('#add-commission');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
