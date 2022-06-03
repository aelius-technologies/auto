@extends('template.app')

@section('meta')
@endsection

@section('title')
    Assign Inventory
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
                        <li class="breadcrumb-item text-muted active" aria-current="page">Assign</li>
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
                    <form action="{{ route('inventory.accepted') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        
                        <input type="hidden" name="id" value="{{ $id }}">

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="obf_id">Customer</label>
                                <select class="form-control" name="obf_id" id="obf_id">
                                    <option value="">Select customer</option>
                                    @if(isset($data) && $data->isNotEmpty())
                                        @foreach($data as $row)
                                            <option value="{{ $row->id }}"> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error obf_id"></span>
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