@extends('admin.layouts.app')

@push('style')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two" id="dataTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Id')</th>
                                    <th scope="col">@lang('Username')</th>
                                    <th scope="col">@lang('First Name')</th>
                                    <th scope="col">@lang('Last Name')</th>
                                    <th scope="col">@lang('Email')</th>
                                    <th scope="col">@lang('Mobile')</th>
                                    <th scope="col">@lang('Created At')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [0, 'desc'],
                "ajax": {
                    "url": "{{ route('admin.users.data', $data_type) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "username"
                    },
                    {
                        "data": "firstname"
                    },
                    {
                        "data": "lastname"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "mobile"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "action"
                    }
                ]

            });
        });

        $(document).on('click', '.js-enable-disable-user', function() {
            $.ajax({
                url: "{{ route('admin.users.activation', '') }}/" + $(this).data('id'),
                success: function(data) {
                    console.log(data);
                }
            });
            $('#dataTable').DataTable().ajax.reload(null, false);
        });
    </script>
@endpush
