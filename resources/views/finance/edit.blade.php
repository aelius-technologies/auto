@extends('template.app')

@section('meta')
@endsection

@section('title')
    Finance Update
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Finance Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('finance') }}" class="text-muted">Finance Master</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Update</li>
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
                    <form action="{{ route('finance.update') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Company Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Company Name" value="{{ $data->name ??'' }}">
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="branch_id">Branch ID</label>
                                <select name="branch_id" id="branch_id" class="form-control">
                                    @if(isset($branch) && $branch->isNotEmpty())
                                        @foreach($branch AS $row)
                                            <option value="{{ $row->id }}" @if($data->branch_id == $row->id) selected @endif>{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error branch_id"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="dsa_or_broker">DSA / Broker</label>
                                <select name="dsa_or_broker" id="dsa_or_broker" class="form-control">
                                    <option value="">Select DSA/Broker</option>
                                    <option value="yes" @if($data->dsa_or_broker == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if($data->dsa_or_broker == 'no') selected @endif>No</option>
                                </select>
                                <span class="kt-form__help error dsa_or_broker"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                            <a href="{{ route('finance') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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