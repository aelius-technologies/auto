@extends('template.app')

@section('meta')
@endsection

@section('title')
    Car Exchange Product view
@endsection

@section('styles')

@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Car Exchange Product Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('car_exchange_product') }}" class="text-muted">Car Exchange Product Master</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">view</li>
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
                    <form  name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Category Name" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="category">Select Category</label>
                                <select name="category" id="category" class="form-control" placeholder="Enter Category Name" disabled>
                                    <option value="">Select Category</option>
                                    @if(isset($category) && $category->isNOtEmpty())
                                        @foreach($category AS $row)
                                            <option value="{{ $row->id }}" @if($data->category_id == $row->id) selected @endif> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error category"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('car_exchange_product') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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