@extends('template.app')

@section('meta')
@endsection

@section('title')
    Product View
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
                        <li class="breadcrumb-item"><a href="{{ route('product') }}" class="text-muted">Car Master</a></li>
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
                    <form action="" name="form" id="form" method="post" enctype="multipart/form-data">
                        <div class="row">
                        <div class="form-group col-sm-6">
                                <label for="name">Car Model</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Car Model" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="veriant">Car Variant</label>
                                <input type="text" name="veriant" id="veriant" class="form-control" placeholder="Enter Car Variant" value="{{ $data->veriant ??'' }}" disabled>
                                <span class="kt-form__help error veriant"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exterior_color">Exterior Color</label>
                                <input type="text" name="exterior_color" id="exterior_color" class="form-control" placeholder="Enter Exterior Color" value="{{ $data->exterior_color ??'' }}" disabled>
                                <span class="kt-form__help error exterior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="interior_color">Interior Color</label>
                                <input type="text" name="interior_color" class="form-control" placeholder="Enter Interior Color" value="{{ $data->interior_color ??'' }}" disabled>
                                <span class="kt-form__help error interior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="category_id">Car Type</label>
                                <select class="form-control" name="category_id" id="category_id" disabled>
                                    <option value="">{{ $data->category_id ??'' }}</option>
                                </select>
                                <span class="kt-form__help error category_id"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="ex_showroom_price">Ex-Showroom Price</label>
                                <input type="number" name="ex_showroom_price" class="form-control" placeholder="Enter Ex-Showroom Price" value="{{ $data->ex_showroom_price ??'' }}" disabled>
                                <span class="kt-form__help error ex_showroom_price"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="is_applicable_for_mcp">My Convinience Program</label>
                                <select class="form-control" name="is_applicable_for_mcp" id="is_applicable_for_mcp" disabled>
                                    <option value="">{{ $data->is_applicable_for_mcp ??'' }}</option>
                                </select>
                                <span class="kt-form__help error is_applicable_for_mcp"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('user') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection