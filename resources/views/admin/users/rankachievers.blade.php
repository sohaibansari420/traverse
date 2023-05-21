@extends('admin.layouts.app')
@section('panel')
    @php
        $ranks = \App\Models\Rank::where('id', '!=', 1)->get();
    @endphp
    <div class="row">
        @foreach ($ranks as $rank)
            <div class="col-12">
                <div style="background-color: #001525;"  class="card-header py-3 text-white">
                    <h3 class="card-title mb-0 text-white"><div class="caption uppercase bold">{{$rank->name}} Ranks Achievers</div></h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 table-responsive">
                            @php
                                $users = \App\Models\User::where('rank_id', $rank->id)->get();
                                $i = 1;
                            @endphp
                            <table class="table activate-select nowrap table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Sponsor</th>
                                    <th>Balance</th>
                                    <th>Binary</th>
                                    <th>Status</th>
                                    <th>Package</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user_mem)
                                    @php

                                        $package = \App\Models\PurchasedPlan::where('user_id', $user_mem->id)->first();
                                        if($package){
                                            $package = $package->package_name;
                                        }
                                        else{
                                            $package = 'No Package';
                                        }

                                        $reff = \App\Models\User::where('id', $user_mem->referrer_id)->first();
                                        if($reff){
                                            $refff = $reff->username;
                                        }
                                        else{
                                            $refff = 'root';
                                        }

                                        $rank = \App\Models\Rank::where('id', round($user_mem->rank_id))->first()->name;

                                        $status = $user_mem->status;
                                        if($status == 1){
                                            $status = 'Active';
                                        }
                                        else{
                                            $status = 'in-Active';
                                        }

                                        $mem = \App\Models\UserExtra::where('user_id', $user_mem->id)->first();
                                        if($mem){
                                            if($mem->binary_active == 1){
                                                $binary = 'Active';
                                            }
                                            else{
                                                $binary = 'In-Active';
                                            }
                                        }
                                        else{
                                            $binary = 'In-Active';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$user_mem->username}}</td>
                                        <td>{{$refff}}</td>
                                        <td>{{round($user_mem->balance, 2)}}</td>
                                        <td>{{$binary}}</td>
                                        <td>{{$status}}</td>
                                        <td>{{$package}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div><!-- end col -->
        @endforeach
        <div class="col-12">
            <div style="background-color: #001525;"  class="card-header py-3 text-white">
                <h3 class="card-title mb-0 text-white"><div class="caption uppercase bold">Reward Achievers Details</div></h3>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12 table-responsive">
                        <table class="table activate-select nowrap table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Rank</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Send Date</th>
                                <th>Achievement Date</th>
                            </tr>
                            </thead>
                            @php
                                $achievers = \App\Models\RankAchiever::orderBy('id', 'desc')->get();
                                $i = 1;
                            @endphp
                            <tbody>
                            @foreach ($achievers as $achiever)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{\App\Models\User::where('id', $achiever->user_id)->first()->username}}</td>
                                    <td>{{\App\Models\Rank::where('id', $achiever->rank_id)->first()->name}}</td>
                                    <td>{{$achiever->reward}}</td>
                                    <td>
                                        @if ($achiever->is_sent == 0)
                                            <span class="badge badge-dark">Pending</span>
                                        @else
                                            <span class="badge badge-success">Sent</span>
                                        @endif
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($achiever->send_date)->toDateString()}}</td>
                                    <td>{{\Carbon\Carbon::parse($achiever->created_at)->toDateString()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div><!-- end col -->
    </div>
    <!-- end row -->
@endsection



@push('script')
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
    </script>
@endpush
