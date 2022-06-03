@extends('template.app')

@section('meta')
@endsection

@section('title')
    Car Exchange View
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Car Exchange Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('car_exchange') }}" class="text-muted">Car Exchange Master</a></li>
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
                    <form action="{{ route('car_exchange.update') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Product Name</label>
                                <select  name="name" id="name" class="form-control" placeholder="Enter Category Name" disabled>
                                    <option value="">Select product</option>
                                    @if(isset($product) && $product->isNOtEmpty())
                                        @foreach($product AS $row)
                                            <option value="{{ $row->id }}" @if($data->product_id == $row->id) selected @endif> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                
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
                            <div class="form-group col-sm-6">
                                <label for="engine_number">Engine Number</label>
                                <input type="text" class="form-control" id="engine_number" name="engine_number" value="{{$data->engine_number ?? ''}}" disabled>
                                <span class="kt-form__help error engine_number"></span>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="chassis_number">Chassis Number</label>
                                <input type="text" class="form-control" id="chassis_number" name="chassis_number" value="{{$data->chassis_number ?? ''}}" disabled>
                                <span class="kt-form__help error chassis_number"></span>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" name="price" value="{{$data->price ?? ''}}" disabled>
                                <span class="kt-form__help error price"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            
                            <a href="{{ route('car_exchange') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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
    $("#price").keyup(function(e){
        $('.price').html('');
        if (!$.isNumeric($(this).val())){
            $('.price').html('');
            $('.price').html('Enter Valid Numeric Value!');
            $('#price').val('');
        }else{
            $('.price').html('');
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