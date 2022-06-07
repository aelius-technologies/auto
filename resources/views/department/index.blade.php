@extends('template.app')

@section('meta')
@endsection

@section('title')
Department Master
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Department Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('department') }}" class="text-muted">Department Master</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
                <!-- Export -->
                    <button class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Export</button>
                <!-- Export -->
                @canany(['department-create'])
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('department.create') }}">Add New</a>
                @endcanany
            </div>
        </div>
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
            <div class="col-md-12 align-self-center collapse" id="collapseExample">
                <div class="customize-input float-right">
                    <form action="{{ route('department.export') }}" method="get">
                        <div class="row">
                            <div class="form-group col-md-6 ">
                                <select name="slug" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="deleted">Deleted</option>
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
                <div class="card-body">
                    <table class="table table-bordered data-table" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Department Name</th>
                                <th>Branch</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>Authorised Person</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
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
                                    "url": "{{ route('department') }}",
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
                                        data: 'branch_name',
                                        name: 'branch_name'
                                    },
                                    {
                                        data: 'email',
                                        name: 'email'
                                    },
                                    {
                                        data: 'number',
                                        name: 'number'
                                    },
                                    {
                                        data: 'authorised_person',
                                        name: 'authorised_person'
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
                        "url": "{!! route('department.change.status') !!}",
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