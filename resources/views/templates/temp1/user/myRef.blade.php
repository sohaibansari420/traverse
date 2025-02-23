@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Sl')</th>
                                    <th scope="col">@lang('Username')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Package')</th>
                                    <th scope="col">@lang('Referral')</th>
                                    <th scope="col">@lang('Level')</th>
                                    <th scope="col">@lang('Email')</th>
                                    {{-- <th scope="col">@lang('Mobile')</th> --}}
                                    <th scope="col">@lang('Join Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $key => $data)
                                    @php
                                        $mem_user = App\Models\User::where('id', $data->mem_id)->firstOrFail();
                                        if ($data->plan_id != 0) {
                                            $plan = App\Models\Plan::where('id', $data->plan_id)->firstOrFail()->name;
                                        } else {
                                            $plan = 'Free';
                                        }
                                    @endphp
                                    <tr>
                                        <td data-label="@lang('Sl')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Username')">{{ $mem_user->username }}</td>
                                        <td data-label="@lang('Name')">{{ $mem_user->fullname }}</td>
                                        <td data-label="@lang('Package')">{{ $plan }}</td>
                                        <td data-label="@lang('Referral')">
                                            {{ App\Models\User::where('id', $mem_user->ref_id)->firstOrFail()->username }}
                                        </td>
                                        <td data-label="@lang('Level')">{{ $data->level }}</td>
                                        <td data-label="@lang('Email')">{{ $mem_user->email }}</td>
                                        {{-- <td data-label="@lang('Mobile')">{{ $mem_user->mobile }}</td> --}}
                                        <td data-label="@lang('Join Date')">
                                            @if ($data->created_at != '')
                                                {{ showDateTime($mem_user->created_at) }}
                                            @else
                                                @lang('Not Assign')
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            document.body.addEventListener('click', copy, true);

            function copy(e) {
                var
                    t = e.target,
                    c = t.dataset.copytarget,
                    inp = (c ? document.querySelector(c) : null);
                if (inp && inp.select) {
                    inp.select();
                    try {
                        document.execCommand('copy');
                        inp.blur();
                        t.classList.add('copied');
                        setTimeout(function() {
                            t.classList.remove('copied');
                        }, 1500);
                        notify('success', 'Copied successfully');
                    } catch (err) {
                        alert(`@lang('Please press Ctrl/Cmd+C to copy')`);
                    }
                }
            }
        })(jQuery);
    </script>
@endpush

@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

