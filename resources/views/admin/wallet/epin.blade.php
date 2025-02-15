@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('Created By')</th>
                                    <th scope="col">@lang('Updated By')</th>
                                    <th scope="col">@lang('Epin')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($epins as $trx)
                                    <tr>
                                        <td data-label="@lang('Date')">{{ showDateTime($trx->created_at) }}</td>
                                        <td data-label="@lang('Created By')"><a
                                                href="{{ route('admin.users.detail', $trx->created_by) }}">{{ @$trx->createdBy->username }}</a>
                                        </td>
                                        <td data-label="@lang('Updated By')"><a
                                                href="@if ($trx->updated_by) {{ route('admin.users.detail', $trx->updated_by) }} @endif">{{ @$trx->updatedBy->username }}</a>
                                        </td>
                                        <td data-label="@lang('Epin')" class="font-weight-bold">
                                            {{ @$trx->epin }}
                                        </td>
                                        <td data-label="@lang('Amount')" class="font-weight-bold">
                                            {{ getAmount(@$trx->amount) }}
                                        </td>

                                        <td data-label="@lang('Status')" class="font-weight-bold">
                                            @if ($trx->status == 1)
                                                <span class="badge badge-pill badge-danger">Used</span>
                                            @else
                                                <span class="badge badge-pill badge-success">Active</span>
                                            @endif
                                        </td>
                                        
                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn edit" data-toggle="tooltip"
                                                data-id="{{ $trx->id }}" data-status="{{ $trx->status }}" data-original-title="Edit">
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
            </div><!-- card end -->
        </div>
    </div>
    
    {{-- edit modal --}}
    <div id="edit-epins" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Epins')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post" action="{{ route('admin.epin.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input class="form-control epin_id" type="hidden" name="id">

                        <div class="form-row">
                            <div class="form-group col-md-12 status">
                                <label class="font-weight-bold">@lang('Status')</label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                    name="status" checked>
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
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.edit').on('click', function() {
                var modal = $('#edit-epins');

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

        })(jQuery);
    </script>
@endpush
