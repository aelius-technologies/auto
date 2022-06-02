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
                                <label for="name">Branch Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Lead Name" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="Enter City" value="{{ $data->city ??'' }}" disabled>
                                <span class="kt-form__help error city"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Enter Address" value="{{ $data->address ??'' }}" disabled>
                                <span class="kt-form__help error address"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="{{ $data->email ??'' }}" disabled>
                                <span class="kt-form__help error email"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Enter Contact Number" value="{{ $data->contact_number ??'' }}" disabled>
                                <span class="kt-form__help error contact_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="manager">Manager</label>
                                <input type="text" name="manager" id="manager" class="form-control" placeholder="Enter Manager" value="{{ $data->manager ??'' }}" disabled>
                                <span class="kt-form__help error manager"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="manager_contact_number">Manager Contact Number</label>
                                <input type="number" name="manager_contact_number" id="manager_contact_number" class="form-control" placeholder="Enter Manager Contact Number" value="{{ $data->manager_contact_number ??'' }}" disabled>
                                <span class="kt-form__help error manager_contact_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="gst">GSTIN</label>
                                <input type="text" name="gst" id="gst" class="form-control" placeholder="Enter GSTIN" value="{{ $data->gst ??'' }}" disabled>
                                <span class="kt-form__help error gst"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('branches') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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