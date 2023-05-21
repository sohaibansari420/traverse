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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
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
                    {{ $transactions->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <form action="{{ route('admin.report.epin.search') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Search Created By')"
                value="{{ $search ?? '' }}">
            <input type="hidden" name="type" value="{{ @$type }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
