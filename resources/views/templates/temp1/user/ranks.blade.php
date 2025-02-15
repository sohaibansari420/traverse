@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        @php
            if ($next_rank) {
                $rem_points = $next_rank->points - getRankPoints(Auth::id());
                if ($rem_points < 0) {
                    $rem_points = 0;
                }
            } else {
                $rem_points = 0;
            }
            
            if (getRankPoints(Auth::id()) < $next_rank->points) {
                $pt = (getRankPoints(Auth::id()) / $next_rank->points) * 100;
            } else {
                $pt = 100;
            }
            
            $tt = round($pt, 1);
            $ts = round($tt / 100, 2);
        @endphp
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
            <div class="card overflow-hidden">
                <div class="text-center p-3">
                    <h2 class="mt-3 mb-1 text-white">Ranks & Rewards</h2>
                </div>
                <div class="card-body text-center">
                    <p><strong class="text-success">{{ $rem_points }} BV</strong> Remaining to Achieve Next Rank</p>
                    <div class="row mt-4">
                        <div class="col text-center">
                            <h5 class="font-weight-semibold mb-1">Current Rank</h5>
                            <p class="mb-2">{{ $rank->name }}</p>
                        </div><!-- col -->
                        <div class="col border-left text-center">
                            <h5 class="font-weight-semibold  mb-1">Next Rank</h5>
                            <p class="mb-2">{{ $next_rank->name }}</p>
                        </div><!-- col -->
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <h6>{{ $tt }}%</h6>
                            <span>{{ $next_rank->name }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ $ts }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Business Volume')</th>
                                    <th scope="col">@lang('Reward')</th>
                                    <th scope="col">@lang('Value')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ranks as $key => $rank)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $key + 1 }}</td>
                                        <td data-label="@lang('Name')">{{ $rank->name }}</td>
                                        <td data-label="@lang('Business Volume')">{{ getAmount($rank->points) }} BV</td>
                                        <td data-label="@lang('Reward')">{{ $rank->reward }}</td>
                                        <td data-label="@lang('Value')">${{ $rank->value }}</td>
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

@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
