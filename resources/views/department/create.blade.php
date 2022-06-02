@extends('template.app')

@section('meta')
@endsection

@section('title')
    Department Create
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Department Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('department') }}" class="text-muted">Department Master</a></li>
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
                    <form action="{{ route('department.insert') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Department Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Department Name">
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Branch</label>
                                    <select name="branch" id="branch" class="form-control">
                                        <option value="">Select Branch</option>
                                            @if(isset($branch) && $branch->isNotEmpty())
                                                @foreach($branch AS $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                <span class="kt-form__help error branch"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address">
                                <span class="kt-form__help error email"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="number">Contact Number</label>
                                <input type="number" name="number" id="number" class="form-control" placeholder="Enter Contact Number">
                                <span class="kt-form__help error number"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Authorised Person</label>
                                    <input name="authorised_person" id="authorised_person" class="form-control" placeholder="Enter Authorised Person">
                                <span class="kt-form__help error authorised_person"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                            <a href="{{ route('department') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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