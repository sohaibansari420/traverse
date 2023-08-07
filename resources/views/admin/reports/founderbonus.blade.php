@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div style="background-color: #001525;"  class="card-header py-3 text-white">
                <h3 class="card-title mb-0 text-white"><div class="caption uppercase bold">{{$page_title}}</div></h3>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12 table-responsive">
                        <table class="table activate-select nowrap table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Month</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($founder as $item)
                                    <tr>
                                        <td>{{\Carbon\Carbon::parse($item->created_at)->format('F')}}</td>
                                        <td>{{$item->username}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{$item->status}}</td>
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
    <div id="founder_bonus" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Founders Bonuses')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.save.founders')}}" method="POST" aria-multiline="">
                    @csrf
                    <input type="hidden" name="code">
                    <div class="modal-body row">
                        <div class="col-md-12 mb-3">
                            <label for="bonus_amount">Founder Bonus Amount</label>
                            <input type="number" id="bonus_amount" name="bonus_amount" required>
                        </div>
                        <div class="col-md-12 founder_members">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" onclick="SendFounderBonus()" class="btn btn-sm btn--success add-commission" style = "cursor: pointer;">
        <i class="fa fa-fw fa-plus"></i>@lang('Send Bonus to Founders')
    </a>
@endpush

@push('script')
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
        function SendFounderBonus(){
            $('#founder_bonus').find('.modal-body .founder_members').html('');

            $.ajax({
                url: "{{ route('admin.get.founders') }}",
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log(data);

                    var table_html='';
                    
                    if (data.length <= 0) {
                        alert('There is no founder member');
                        return ;
                    }
                    
                    data.forEach(function(value,key){
                        if (value.active_plan.length > 0) {   
                            table_html ='<div>'+
                                            '<div class="row">'+
                                                '<input type="text" class="col-md-1" name="id[]" value="'+value.id+'" readonly>'+
                                                '<input type="text" class="col-md-3" name="username[]" value="'+value.username+'" readonly>'+
                                                '<input type="text" class="col-md-4" name="email[]" value="'+value.email+'" readonly>'+
                                            '</div>'+
                                        '</div>';
                            
                            $('#founder_bonus').find('.modal-body .founder_members').append(table_html);
                        }
                    })
                    $('#founder_bonus').modal('show');
                }
            });
        }
    </script>
@endpush
