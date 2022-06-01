@extends('template.app')

@section('meta')
@endsection

@section('title')
Transfers
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Transfers</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transfer') }}" class="text-muted">Transfers</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
                @canany(['transfer-create'])
                    <a class="btn waves-effect waves-light btn-rounded btn-outline-primary pull-right" href="{{ route('transfer.create') }}">Add New</a>
                @endcanany
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
                                <th>Product</th>
                                <th>From Branch</th>
                                <th>To Branch</th>
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
                    "url": "{{ route('transfer') }}",
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
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'from_branch',
                        name: 'from_branch'
                    },
                    {
                        data: 'to_branch',
                        name: 'to_branch'
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

    $(document).on("submit", ".transfer_reject",function(e){
        e.preventDefault();
        let form = $(this).serializeArray();
        let id = form[0].value;
        let reason = form[1].value;

        $.ajax({
            "url": "{!! route('transfer.reject') !!}",
            "dataType": "json",
            "type": "POST",
            "data":{
                id: id,
                reason: reason,
                _token: "{{ csrf_token() }}"
            },
            success: function (response){
                if (response.code == 200){
                    $('.modal').modal('hide');
                    datatable.ajax.reload();
                    toastr.success('Status chagned successfully', 'Success');
                }else{
                    toastr.error('Failed to change status', 'Error');
                }
            }
        });
    }); 
</script>
@endsection