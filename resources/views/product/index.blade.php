@extends('template.app')

@section('meta')
@endsection

@section('title')
Car Master
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Car Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products') }}" class="text-muted">Car Master</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
        <div class="customize-input float-right">
                    <!-- Import -->
                        <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" data-toggle="modal" data-target="#exampleModal" href="Javascript:void(0)">Import Products</a>
                    <!-- Import -->
                    
                    <!-- Export -->
                        <button class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Export Products
                        </button>
                    <!-- Export -->
                @canany(['products-create'])
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('products.create') }}">Add New</a>
                @endcanany
            </div>
        </div>
        <div class="col-md-12 align-self-center collapse" id="collapseExample">
                <div class="customize-input float-right">
                    <form action="{{ route('products.export') }}" method="get">
                        @csrf
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
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered data-table" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Model</th>
                                <th>Variant</th>
                                <th>Ext. Color</th>
                                <th>Int. Color</th>
                                <th>Ex-Showroom Price</th>
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
                                    "url": "{{ route('products') }}",
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
                                        data: 'veriant',
                                        name: 'veriant'
                                    },
                                    {
                                        data: 'exterior_color',
                                        name: 'exterior_color'
                                    },
                                    {
                                        data: 'interior_color',
                                        name: 'interior_color'
                                    },
                                    {
                                        data: 'ex_showroom_price',
                                        name: 'ex_showroom_price'
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
                        "url": "{!! route('products.change.status') !!}",
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