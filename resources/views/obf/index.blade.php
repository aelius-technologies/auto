@extends('template.app')

@section('meta')
@endsection

@section('title')
    OBF
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">OBF</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('obf') }}" class="text-muted">OBF</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
                <!-- Import -->
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" data-toggle="modal" data-target="#exampleModal" href="Javascript:void(0)">Import</a>
                <!-- Import -->
                <!-- Export -->
                    <button class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Export</button>
                <!-- Export -->
                @canany(['obf-create'])
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('obf.create') }}">Add New</a>
                @endcanany
            </div>
        </div>
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
            <div class="col-md-12 align-self-center collapse" id="collapseExample">
                <div class="customize-input float-right">
                    <form action="{{ route('obf.export') }}" method="get">
                        <div class="row">
                            <div class="form-group col-md-6 ">
                                <select name="slug" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="all">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="accepted">Accepted</option>
                                    <option value="obf_accepted">OBF Accepted</option>
                                    <option value="account_accepted">Account Accepted</option>
                                    <option value="obf_rejected">OBF Accepted</option>
                                    <option value="account_rejected">ACcount Accepted</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="deleted">Deleted</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered data-table" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Reject Request</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    </div>
                    <form action="{{ route('obf.import') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="first_name">Upload File Here</label>
                                    <input type="file" name="file" id="file" class="form-control" placeholder="Plese upload file here" required>
                                    <span class="kt-form__help error file"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Submit!</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- Modal -->
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var datatable;

        $(document).ready(function() {
            if($('#data-table').length > 0){
                datatable = $('#data-table').DataTable({
                    processing: true,
                    serverSide: true,

                    // "pageLength": 10,
                    // "iDisplayLength": 10,
                    "responsive": true,
                    "aaSorting": [],
                    // "order": [], //Initial no order.
                    //     "aLengthMenu": [
                    //     [5, 10, 25, 50, 100, -1],
                    //     [5, 10, 25, 50, 100, "All"]
                    // ],

                    // "scrollX": true,
                    // "scrollY": '',
                    // "scrollCollapse": false,
                    // scrollCollapse: true,

                    // lengthChange: false,

                    "ajax":{
                        "url": "{{ route('obf') }}",
                        "type": "POST",
                        "dataType": "json",
                        "data":{
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    "columnDefs": [{
                            //"targets": [0, 5], //first column / numbering column
                            "orderable": true, //set not orderable
                        },
                    ],
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },

                        {
                            data: 'booking_date',
                            name: 'booking_date'
                        },
                        {
                            data: 'customer_name',
                            name: 'customer_name'
                        },
                        {
                            data: 'product',
                            name: 'product'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                        },
                    ]
                });
            }
        });

        function change_status(object){
            var id = $(object).data("id");
            var status = $(object).data("status");
            if (confirm('Are you sure you want to delete?')) {
                $.ajax({
                    "url": "{!! route('obf.change_status') !!}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{
                        id: id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response){
                        if (response.code == 200){
                            datatable.ajax.reload();
                            toastr.success('Status chagned successfully', 'Success');
                        }else{
                            toastr.error('Failed to change status', 'Error');
                        }
                    }
                });
            }
        }
    </script>
@endsection
