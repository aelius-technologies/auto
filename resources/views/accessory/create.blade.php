@extends('template.app')

@section('meta')
@endsection

@section('title')
    Branch Create
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
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
                    <form action="{{ route('accessory.insert') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Lead Name" value="{{ $data->name ??'' }}">
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="type">Type</label>
                                <input type="text" name="type" id="type" class="form-control" placeholder="Enter type" value="{{ $data->type ??'' }}">
                                <span class="kt-form__help error type"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="hsn_number">HSN Number</label>
                                <input type="text" name="hsn_number" id="hsn_number" class="form-control" placeholder="Enter HSN Number" value="{{ $data->hsn_number ??'' }}">
                                <span class="kt-form__help error hsn_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control" placeholder="Enter price" value="{{ $data->price ??'' }}">
                                <span class="kt-form__help error price"></span>
                            </div>
                          
                            <div class="form-group col-sm-6">
                                <label for="model_number">Model Number</label>
                                <input type="text" name="model_number" id="model_number" class="form-control" placeholder="Enter model_number" value="{{ $data->model_number ??'' }}">
                                <span class="kt-form__help error model_number"></span>
                            </div>
                          
                            <div class="form-group col-sm-6">
                                <label for="warranty">Warranty</label>
                                <input type="text" name="warranty" id="warranty" class="form-control" placeholder="Enter Warranty in years" value="{{ $data->warranty ??'' }}">
                                <span class="kt-form__help error warranty"></span>
                            </div>
                        
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
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
<script src="{{ asset('assets/js/promise.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.bundle.js') }}"></script>
<script>
    // Check is Price Numeric?
    $("#price").keyup(function(e){
        $('.price').html('');
        if ($.isNumeric($(this).val())){
            $('.price').html('');
        }else{
            $('#price').val('');
            $('.price').html('');
            $('.price').html('Enter Valid Value!');
        }
    });
</script>
<script>    
    // Check is Warranty Numeric?
    $("#warranty").keyup(function(e){
        $('.warranty').html('');
        if ($.isNumeric($(this).val())){
            $('.warranty').html('');
        }else{
            $('#warranty').val('');
            $('.warranty').html('');
            $('.warranty').html('Enter Valid Number!');
        }
    });
</script>
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