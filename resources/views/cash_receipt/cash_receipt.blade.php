@extends('template.app')

@section('meta')
@endsection

@section('title')
    Cash Receipt Generate
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Cash Receipt Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('cash_receipt') }}" class="text-muted">Cash Receipt Master</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Create</li>
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
                    <form>
                        @csrf
                        @method('POST')
                        <div class="row" id="print">
                            <div class="col-md-12">
                                <h1 class="text-center">Cash Receipt</h1>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="customer_name"><b>Customer Name:</b></label>
                                <span class="form-lable">{{ $data->customer_name ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="model_name"><b>Model Name:</b></label>
                                <span class="">{{ $data->product_name ?? 'N/A' }}</span>
                            </div>
                        
                            <div class="form-group col-sm-6">
                                <label for="booking_amount"><b>Booking Amount:</b></label>
                                <span class="">{{ $data->amount ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="remark"><b>Remark:</b></label>
                                <span class="">{{ $data->reason ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <span onclick="printDiv('#print')" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Print</span>
                            <!-- <a href="{{ route('cash_receipt') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/promise.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.bundle.js') }}"></script>

<script>
    function printDiv(elem){
        renderMe($('<div/>').append($(elem).clone()).html());
    }
    function renderMe(data) {
        var link = "{{ asset('assets/css/sb-admin-2.min.css') }}";
        var mywindow = window.open('', 'invoice-box', 'height=1000,width=1000');
        mywindow.document.write('<html><head><title>invoice-box</title>');
        mywindow.document.write('<link rel="stylesheet" href="'+link+'" type="text/css" />');
        mywindow.document.write('</head><body > <h1>Cash Receipt</h1> ');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');


        setTimeout(function () {
        mywindow.print();
        mywindow.close();
        }, 1000)
        return true;
    }
</script>
@endsection