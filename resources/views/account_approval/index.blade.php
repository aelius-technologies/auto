@extends('template.app')

@section('meta')
@endsection

@section('title')
Account Approvals
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Account Approval</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('account_approval') }}" class="text-muted">Account Approval</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">List</li>
                    </ol>
                </nav>
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
                                <th>Booking Date</th>
                                <th>Model</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="DescModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Reject Request</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    </div>
                    <form action="">
                        <div class="modal-body">

                            <input type="hidden" name="id" id="id" value="">
                            <input type="hidden" name="status" id="status" value="">
                            <label for="">Enter Your Reason</label>
                            <textarea placeholder="Please Enter Your Reason" class="form-control" name="reason" id="reason" cols="30" rows="10"></textarea>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default " onclick="status_reject(this);">Apply!</button>
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
                    "url": "{{ route('account_approval') }}",
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
                        data: 'booking_date',
                        name: 'booking_date'
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

    function change_status(object) {
        var id = $(object).data("id");
        var status = $(object).data("status");

        if (confirm('Are you sure?')) {
            $.ajax({
                "url": "{!! route('account_approval.change.status') !!}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    id: id,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response.code);
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

    function change_status(object) {
        var id = $(object).data("id");
        var status = $(object).data("status");

        if (confirm('Are you sure?')) {
            $.ajax({
                "url": "{!! route('account_approval.change.status') !!}",
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

    function change_status_reject(object) {
        var id = $(object).data("id");
        var status = $(object).data("status");
        $('#DescModal').modal("show");
        $('#id').val(id);
        $('#status').val(status);
    }

    function status_reject(object) {
        var id = $('#id').val();
        var status = $('#status').val();
        var reason = $('#reason').val();
        $('#DescModal').modal("hide");
        $('#id').val('');
        $('#status').val('');
        $.ajax({
            "url": "{!! route('account_approval.change.status') !!}",
            "dataType": "json",
            "type": "POST",
            "data": {
                id: id,
                status: status,
                reason: reason,
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
</script>
@endsection