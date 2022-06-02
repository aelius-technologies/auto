@extends('template.app')

@section('meta')
@endsection

@section('title')
    Transfer Detail - Transfer
@endsection

@section('styles')
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
                        <li class="breadcrumb-item text-muted active" aria-current="page">Transfer Detail</li>
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
                    <div class="row" id="print">
                        <div class="form-group col-sm-6">
                            <label for="from_branch">From Branch</label>: <span id="from_branch">{{ $data->from_branch ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="to_branch">To Branch</label>: <span id="to_branch">{{ $data->to_branch ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="name">Name</label>: <span id="name">{{ $data->name ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="veriant">Veriant</label>: <span id="veriant">{{ $data->veriant ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="key_number">Key Number</label>: <span id="key_number">{{ $data->key_number ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="engine_number">Engine Number</label>: <span id="engine_number">{{ $data->engine_number ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="chassis_number">Chassis Number</label>: <span id="chassis_number">{{ $data->chassis_number ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="vin_number">Vin Number</label>: <span id="vin_number">{{ $data->vin_number ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="ex_showroom_price">EX Showroom Price</label>: <span id="ex_showroom_price">{{ $data->ex_showroom_price ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="interior_color">Interior Color</label>: <span id="interior_color">{{ $data->interior_color ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exterior_color">Exterior Color</label>: <span id="exterior_color">{{ $data->exterior_color ?? '' }}</span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="transfer_fee">Transfer Fee</label>: <span id="transfer_fee">{{ $data->transfer_fee ?? '' }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('transfer') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection

