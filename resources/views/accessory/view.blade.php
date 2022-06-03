@extends('template.app')

@section('meta')
@endsection

@section('title')
    Branch View
@endsection

@section('styles')

@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Branch Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('branches') }}" class="text-muted">Branch Master</a></li>
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
                    <form action="{{ route('branches.view') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('GET')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Lead Name" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="type">Type</label>
                                <input type="text" name="type" id="type" class="form-control" placeholder="Enter type" value="{{ $data->type ??'' }}" disabled>
                                <span class="kt-form__help error type"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="hsn_number">HSN Number</label>
                                <input type="text" name="hsn_number" id="hsn_number" class="form-control" placeholder="Enter HSN Number" value="{{ $data->hsn_number ??'' }}" disabled>
                                <span class="kt-form__help error hsn_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control" placeholder="Enter price" value="{{ $data->price ??'' }}" disabled>
                                <span class="kt-form__help error price"></span>
                            </div>
                          
                            <div class="form-group col-sm-6">
                                <label for="model_number">Model Number</label>
                                <input type="text" name="model_number" id="model_number" class="form-control" placeholder="Enter model_number" value="{{ $data->model_number ??'' }}" disabled>
                                <span class="kt-form__help error model_number"></span>
                            </div>
                          
                            <div class="form-group col-sm-6">
                                <label for="warranty">Warranty</label>
                                <input type="text" name="warranty" id="warranty" class="form-control" placeholder="Enter Warranty in years" value="{{ $data->warranty ??'' }}" disabled>
                                <span class="kt-form__help error warranty"></span>
                            </div>
                        
                        </div>
                        <div class="form-group">
                            <a href="{{ route('accessory') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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