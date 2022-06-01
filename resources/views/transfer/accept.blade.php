@extends('template.app')

@section('meta')
@endsection

@section('title')
    Transter Accept
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Transters</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transfer') }}" class="text-muted">Transters</a></li>
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
                <form action="{{ route('transfer.accept') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <input type="hidden" name="id" value="{{ $id }}">

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="product">Select Product</label>
                                <select class="form-control" name="product" id="product">
                                    <option value="">Select Product</option>
                                    @if(isset($inventories) && $inventories->isNotEmpty())
                                        @foreach($inventories as $row)
                                            <option value="{{ $row->id }}"> {{ $row->name }} - {{ $row->veriant }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error product"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="transfer_fee">Transfer Fee</label>
                                <input type="text" name="transfer_fee" id="transfer_fee" class="form-control digit">
                                <span class="kt-form__help error transfer_fee"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="name">Name</label>: <span id="name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="veriant">Veriant</label>: <span id="veriant"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="key_number">Key Number</label>: <span id="key_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="engine_number">Engine Number</label>: <span id="engine_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="chassis_number">Chassis Number</label>: <span id="chassis_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="vin_number">Vin Number</label>: <span id="vin_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="ex_showroom_price">EX Showroom Price</label>: <span id="ex_showroom_price"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="interior_color">Interior Color</label>: <span id="interior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exterior_color">Exterior Color</label>: <span id="exterior_color"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="status">Status</label>: <span id="status"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                            <a href="{{ route('transfer') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Cancel</a>
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
    $(document).ready(function(){
        $('.digit').keyup(function(e){
            if (/\D/g.test(this.value)){
                this.value = this.value.replace(/\D/g, '');
            }
        });

        $('#product').change(function() {    
            let id = $(this).val();

            $.ajax({
                url: "{!! route('transfer.inventory.details') !!}"+"?id="+id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.code == 200){
                        data = response.data;

                        $('#name').html(data.name);
                        $('#veriant').html(data.veriant);
                        $('#key_number').html(data.key_number);
                        $('#engine_number').html(data.engine_number);
                        $('#chassis_number').html(data.chassis_number);
                        $('#vin_number').html(data.vin_number);
                        $('#ex_showroom_price').html(data.ex_showroom_price);
                        $('#interior_color').html(data.interior_color);
                        $('#exterior_color').html(data.exterior_color);
                        $('#status').html(data.status);
                    }else{
                        $('#name').html('');
                        $('#veriant').html('');
                        $('#key_number').html('');
                        $('#engine_number').html('');
                        $('#chassis_number').html('');
                        $('#vin_number').html('');
                        $('#ex_showroom_price').html('');
                        $('#interior_color').html('');
                        $('#exterior_color').html('');
                        $('#status').html('');
                    }
                }
            });
        });
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

