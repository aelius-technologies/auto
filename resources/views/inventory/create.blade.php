@extends('template.app')

@section('meta')
@endsection

@section('title')
    Create Inventory
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Inventories</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory') }}" class="text-muted">Inventories</a></li>
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
                    <form action="{{ route('inventory.insert') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="category_id">Category</label>
                                <select class="form-control" name="category_id" id="category_id">
                                    <option value="">Select category</option>
                                    @if(isset($categories) && $categories->isNotEmpty())
                                        @foreach($categories as $row)
                                            <option value="{{ $row->id }}"> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error category_id"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="branch_id">Branch</label>
                                <select class="form-control" name="branch_id" id="branch_id">
                                    <option value="">Select Branch</option>
                                    @if(isset($branches) && $branches->isNotEmpty())
                                        @foreach($branches as $row)
                                            <option value="{{ $row->id }}"> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error branch_id"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="veriant">Veriant</label>
                                <input type="text" name="veriant" id="veriant" class="form-control" placeholder="Enter veriant">
                                <span class="kt-form__help error veriant"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="key_number">Key number</label>
                                <input type="text" name="key_number" id="key_number" class="form-control" placeholder="Enter key number">
                                <span class="kt-form__help error key_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="engine_number">Engine number</label>
                                <input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Enter engine number">
                                <span class="kt-form__help error engine_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="chassis_number">Chassis number</label>
                                <input type="text" name="chassis_number" id="chassis_number" class="form-control" placeholder="Enter chassis number">
                                <span class="kt-form__help error chassis_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="vin_number">Vin number</label>
                                <input type="text" name="vin_number" id="vin_number" class="form-control" placeholder="Enter vin number">
                                <span class="kt-form__help error vin_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="ex_showroom_price">EX Showroom price</label>
                                <input type="text" name="ex_showroom_price" id="ex_showroom_price" class="form-control" placeholder="Enter ex showroom price">
                                <span class="kt-form__help error ex_showroom_price"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="interior_color">Interior color</label>
                                <input type="text" name="interior_color" id="interior_color" class="form-control" placeholder="Enter interior color">
                                <span class="kt-form__help error interior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exterior_color">Exterior color</label>
                                <input type="text" name="exterior_color" id="exterior_color" class="form-control" placeholder="Enter exterior color">
                                <span class="kt-form__help error exterior_color"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                            <a href="{{ route('inventory') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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