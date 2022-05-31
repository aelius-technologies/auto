@extends('template.app')

@section('meta')
@endsection

@section('title')
    Transfer Create
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Transfers</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transfer') }}" class="text-muted">Transfers</a></li>
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
                    <form action="{{ route('transfer.insert') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="to_branch">To Branch</label>
                                <select class="form-control" name="to_branch" id="to_branch">
                                    <option value="">Select To Branch</option>
                                    @if(isset($branches) && $branches->isNotEmpty())
                                        @foreach($branches as $row)
                                            <option value="{{ $row->id }}"> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error to_branch"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="product_id">Product</label>
                                <select class="form-control" name="product_id" id="product_id">
                                    <option value="">Select Product</option>
                                    @if(isset($products) && $products->isNotEmpty())
                                        @foreach($products as $row)
                                            <option value="{{ $row->id }}"> {{ $row->name }} - {{ $row->veriant }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error product_id"></span>
                            </div>
                            @if(auth()->user()->roles->pluck('name')[0] == 'admin')
                                <div class="form-group col-sm-6">
                                    <label for="from_branch">From Branch</label>
                                    <select class="form-control" name="from_branch" id="from_branch">
                                        <option value="">Select From Branch</option>
                                        @if(isset($branches) && $branches->isNotEmpty())
                                            @foreach($branches as $row)
                                                <option value="{{ $row->id }}"> {{ $row->name }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="kt-form__help error from_branch"></span>
                                </div>
                            @endif
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
    // $(document).ready(function(){
    //   
    // });
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