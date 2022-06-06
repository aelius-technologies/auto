@extends('template.app')

@section('meta')
@endsection

@section('title')
Users
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Users</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user') }}" class="text-muted">Users</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
                <!-- Import -->
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" data-toggle="modal" data-target="#exampleModal" href="Javascript:void(0)">Import Users</a>
                <!-- Import -->
                
                <!-- Export -->
                    <button class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Export Users
                    </button>
                <!-- Export -->
                
                @canany(['user-create'])
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('user.create') }}">Add New</a>
                @endcanany
            </div>
        </div>
            <div class="col-md-12 align-self-center collapse " id="collapseExample">
                <div class="customize-input float-right">
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('user.export', ['slug' => 'active']) }}">Export Active Users</a>
               
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('user.export', ['slug' => 'inactive']) }}">Export Inactive Users</a>
               
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('user.export', ['slug' => 'deleted']) }}">Export Deleted Users</a>
               
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('user.export') }}">Export All Users</a>
                </div>
            </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered data-table" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Phone</th>
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
                    <form action="{{ route('user.import') }}" name="form" id="form" method="post" enctype="multipart/form-data">
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
                if ($('#data-table').length > 0) {
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

                                "ajax": {
                                    "url": "{{ route('user') }}",
                                    "type": "POST",
                                    "dataType": "json",
                                    "data": {
                                        _token: "{{csrf_token()}}"
                                    }
                                },
                                "columnDefs": [{
                                    //"targets": [0, 5], //first column / numbering column
                                    "orderable": true, //set not orderable
                                }, ],
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex'
                                    },
                                    {
                                        data: 'name',
                                        name: 'name'
                                    },
                                    {
                                        data: 'role',
                                        name: 'role'
                                    },
                                    {
                                        data: 'contact_number',
                                        name: 'contact_number'
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

            function change_status(object) {
                var id = $(object).data("id");
                var status = $(object).data("status");

                if (confirm('Are you sure?')) {
                    $.ajax({
                        "url": "{!! route('user.change.status') !!}",
                        "dataType": "json",
                        "type": "POST",
                        "data": {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.code == 200) {
                                datatable.ajax.reload();
                                toastr.success('Status changed successfully', 'Success');
                            } else {
                                toastr.error('Failed to change status', 'Error');
                            }
                        }
                    });
                }
            }
</script>
@endsection