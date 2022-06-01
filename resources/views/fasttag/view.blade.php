@extends('template.app')

@section('meta')
@endsection

@section('title')
    Fastag View
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Fastag Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fasttag') }}" class="text-muted">Fastag Master</a></li>
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
                    <form action="{{ route('fasttag.view') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('GET')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="tag_id">Tag ID</label>
                                <input type="text" name="tag_id" id="tag_id" class="form-control" placeholder="Enter Tag ID" value="{{ $data->tag_id ??'' }}" disabled>
                                <span class="kt-form__help error tag_id"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter Amount" value="{{ $data->amount ??'' }}" disabled>
                                <span class="kt-form__help error amount"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('fasttag') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection