@extends('template.app')

@section('meta')
@endsection

@section('title')
Orders
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Orders</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('order') }}" class="text-muted">Orders</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
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
                                <th>Order ID</th>
                                <th>Branch</th>
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
                        "url": "{{ route('order') }}",
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
                            data: 'order_id',
                            name: 'order_id'
                        },
                        {
                            data: 'branch_name',
                            name: 'branch_name'
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
</script>
@endsection