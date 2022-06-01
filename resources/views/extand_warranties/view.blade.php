@extends('template.app')

@section('meta')
@endsection

@section('title')
    Extend Warranty View
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Extend Warranty Master</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('extand_warranties') }}" class="text-muted">Extend Warranty Master</a></li>
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
                    <form action="{{ route('extand_warranties.view') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="years">Years</label>
                                <input type="text" name="years" id="years" class="form-control" placeholder="Enter Year" value="{{ $data->years ??'' }}" disabled>
                                <span class="kt-form__help error years"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter Amount" value="{{ $data->amount ??'' }}" disabled>
                                <span class="kt-form__help error amount"></span>
                            </div>
                        </div>
                        <div class="form-group">
                  
                            <a href="{{ route('extand_warranties') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
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