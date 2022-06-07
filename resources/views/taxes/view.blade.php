@extends('template.app')

@section('meta')
@endsection

@section('title')
    Taxes View
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tax Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tax') }}" class="text-muted">Tax Master</a></li>
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
                    <form action="{{ route('tax.view') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('GET')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Tax Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Tax Name" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="percentage">Percentage</label>
                                <input type="text" name="percentage" id="percentage" class="form-control" placeholder="Enter Percentage" value="{{ $data->percentage ??'' }}" disabled>
                                <span class="kt-form__help error percentage"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('tax') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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