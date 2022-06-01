@extends('template.app')

@section('meta')
@endsection

@section('title')
    Insurance View
@endsection

@section('styles')

@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Insurance Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('insurance') }}" class="text-muted">Insurance Master</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">View</li>
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
                    <form action="{{ route('insurance.view') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('GET')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Company Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Company Name" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="years">Years</label>
                                <input type="number" name="years" id="years" class="form-control" placeholder="Enter Years" value="{{ $data->years ??'' }}" disabled>
                                <span class="kt-form__help error years"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="type">Insurance Type</label>
                                <input type="text" name="type" id="type" class="form-control" placeholder="Enter Type" value="{{ $data->type ??'' }}" disabled>
                                <span class="kt-form__help error type"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control" placeholder="Enter Amount" value="{{ $data->amount ??'' }}" disabled>
                                <span class="kt-form__help error amount"></span>
                            </div>
                        </div>
                        <div class="form-group">
               
                            <a href="{{ route('insurance') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection