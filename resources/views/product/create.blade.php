@extends('template.app')

@section('meta')
@endsection

@section('title')
    Product Create
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item"><a href="{{ route('products') }}" class="text-muted">Car Master</a></li>
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
                    <form action="{{ route('products.insert') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Car Model</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Car Model">
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="veriant">Car Variant</label>
                                <input type="text" name="veriant" id="veriant" class="form-control" placeholder="Enter Car Variant">
                                <span class="kt-form__help error veriant"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exterior_color">Exterior Color</label>
                                <input type="text" name="exterior_color" id="exterior_color" class="form-control" placeholder="Enter Exterior Color">
                                <span class="kt-form__help error exterior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="interior_color">Interior Color</label>
                                <input type="text" name="interior_color" class="form-control" placeholder="Enter Interior Color">
                                <span class="kt-form__help error interior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="category_id">Car Type</label>
                                
                                <select class="form-control" name="category_id" id="category_id">
                                    <option value="">Select Car Type</option>
                                    @if(isset($data) && $data->isNotEmpty())
                                        @foreach($data AS $row)
                                            <option value="{{ $row->id }}">{{ $row->name ??'' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error category_id"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="ex_showroom_price">Ex-Showroom Price</label>
                                <input type="number" name="ex_showroom_price" class="form-control" placeholder="Enter Ex-Showroom Price">
                                <span class="kt-form__help error ex_showroom_price"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="is_applicable_for_mcp">Is Applicable For My Convinience Program</label>
                                <select class="form-control" name="is_applicable_for_mcp" id="is_applicable_for_mcp">
                                    <option value="">Select Is Applicable For My Convinience Program</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <span class="kt-form__help error is_applicable_for_mcp"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                            <a href="{{ route('products') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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
    $(document).ready(function() {
        var form = $('#form');
        $('.kt-form__help').html('');
        form.submit(function(e) {
            $('.help-block').html('');
            $('.m-form__help').html('');
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: new FormData($(this)[0]),
                dataType: 'json',
                async: false,
                processData: false,
                contentType: false,
                success: function(json) {
                    return true;
                },
                error: function(json) {
                    if (json.status === 422) {
                        e.preventDefault();
                        var errors_ = json.responseJSON;
                        $('.kt-form__help').html('');
                        $.each(errors_.errors, function(key, value) {
                            $('.' + key).html(value);
                        });
                    }
                }
            });
        });
    });
</script>
@endsection