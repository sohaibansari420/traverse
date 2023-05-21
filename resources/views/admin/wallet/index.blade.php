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
                                    <th scope="col">@lang('Currency')</th>
                                    <th scope="col">@lang('Passive')</th>
                                    <th scope="col">@lang('Deposit')</th>
                                    <th scope="col">@lang('Withdraw')</th>
                                    <th scope="col">@lang('Transfer')</th>
                                    <th scope="col">@lang('Display')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $key => $wallet)
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Name')">{{ __($wallet->name) }}</td>
                                        <td data-label="@lang('Currency')">{{ __($wallet->currency) }}</td>
                                        <td data-label="@lang('Passive')">{{ getAmount($wallet->passive) }}</td>
                                        <td data-label="@lang('Deposit')">
                                            @if ($wallet->deposit == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('True')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('False')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Withdraw')">
                                            @if ($wallet->withdraw == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('True')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('False')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Transfer')">
                                            @if ($wallet->transfer == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('True')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('False')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Display')">
                                            @if ($wallet->display == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('True')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('False')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if ($wallet->status == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $wallet->id }}" data-name="{{ $wallet->name }}"
                                                data-currency="{{ $wallet->currency }}"
                                                data-passive="{{ getAmount($wallet->passive) }}"
                                                data-deposit="{{ $wallet->deposit }}"
                                                data-withdraw="{{ $wallet->withdraw }}"
                                                data-transfer="{{ $wallet->transfer }}" data-icon="{{ $wallet->icon }}"
                                                data-image="{{ $wallet->image }}" data-display="{{ $wallet->display }}"
                                                data-status="{{ $wallet->status }}" data-original-title="Edit">
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
                    {{ $wallets->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div id="edit-wallet" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Wallet')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.wallet.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control wallet_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control name" name="name" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Currency') </label>
                                <input class="form-control currency" name="currency" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Passive') </label>
                                <input class="form-control passive" name="passive" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Icon') </label>
                                <input class="form-control icon" name="icon" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2 deposit">
                                <label class="font-weight-bold">@lang('Deposit')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="deposit" checked>
                            </div>
                            <div class="form-group col-md-2 withdraw">
                                <label class="font-weight-bold">@lang('Withdraw')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="withdraw" checked>
                            </div>
                            <div class="form-group col-md-2 transfer">
                                <label class="font-weight-bold">@lang('Transfer')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="transfer" checked>
                            </div>
                            <div class="form-group col-md-2 display">
                                <label class="font-weight-bold">@lang('Display')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="display" checked>
                            </div>
                            <div class="form-group col-md-2 status">
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
                                                accept=".png, .jpg, .jpeg, .svg" name="image">
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

    <div id="add-wallet" class="modal  fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Wallet')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.wallet.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control wallet_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Name') :</label>
                                <input class="form-control" name="name" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Currency') </label>
                                <input class="form-control" name="currency" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Passive') </label>
                                <input class="form-control" name="passive" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold"> @lang('Icon') </label>
                                <input class="form-control" name="icon" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold">@lang('Deposit')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="deposit" checked>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold">@lang('Withdraw')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="withdraw" checked>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold">@lang('Transfer')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="transfer" checked>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold">@lang('Display')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('True')" data-off="@lang('False')"
                                    name="display" checked>
                            </div>
                            <div class="form-group col-md-2">
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
                                                accept=".png, .jpg, .jpeg, .svg" name="image">
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
    <a href="javascript:void(0)" class="btn btn-sm btn--success add-wallet"><i
            class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
    <a href="{{ route('admin.wallet.adjust') }}" class="btn btn-sm btn--info adjust-wallet"><i
            class="fa fa-fw fa-store-alt"></i>@lang('Adjust Wallet')</a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-wallet');
                modal.find('.name').val($(this).data('name'));
                modal.find('.currency').val($(this).data('currency'));
                modal.find('.passive').val($(this).data('passive'));
                modal.find('.icon').val($(this).data('icon'));
                modal.find('.image').val($(this).data('image'));

                if ($(this).data('deposit')) {
                    modal.find('.deposit .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="deposit"]').prop('checked', true);

                } else {
                    modal.find('.deposit .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="deposit"]').prop('checked', false);
                }

                if ($(this).data('withdraw')) {
                    modal.find('.withdraw .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="withdraw"]').prop('checked', true);

                } else {
                    modal.find('.withdraw .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="withdraw"]').prop('checked', false);
                }

                if ($(this).data('transfer')) {
                    modal.find('.transfer .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="transfer"]').prop('checked', true);

                } else {
                    modal.find('.transfer .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="transfer"]').prop('checked', false);
                }

                if ($(this).data('display')) {
                    modal.find('.display .toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="display"]').prop('checked', true);

                } else {
                    modal.find('.display .toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="display"]').prop('checked', false);
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

            $('.add-wallet').on('click', function() {
                var modal = $('#add-wallet');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style type="text/css">
        .logoPrev {
            background-size: 100%;
        }
    </style>
@endpush
