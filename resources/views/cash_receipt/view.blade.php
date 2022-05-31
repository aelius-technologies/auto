@extends('template.app')

@section('meta')
@endsection

@section('title')
    Cash Receipt View
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Users</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user') }}" class="text-muted">Users</a></li>
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
                    <form action="" name="form" id="form" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="first_name">Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Plese enter first_name" value="{{ $data->first_name ??'' }}" disabled>
                                <span class="kt-form__help error first_name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="last_name">Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Plese enter last_name" value="{{ $data->last_name ??'' }}" disabled>
                                <span class="kt-form__help error last_name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Plese enter email" value="{{ $data->email ??'' }}" disabled>
                                <span class="kt-form__help error email"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Plese enter contact_number" value="{{ $data->contact_number ??'' }}" disabled>
                                <span class="kt-form__help error contact_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="branch">Branch</label>
                                <select class="form-control" name="branch" id="branch" disabled>
                                    <option value="">Select Branch</option>
                                    @if(isset($branch) && $branch->isNotEmpty())
                                        @foreach($branch AS $row)
                                            <option value="{{ $row->id }}" @if($data->branch == $row->id) selected @endif> {{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error branch"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="role" name="role" placeholder="Plese select role">Role</label>
                                <select class="form-control" name="role" id="role" disabled>
                                    <option value="">Select role</option>
                                    @if(isset($roles) && $roles->isNotEmpty())
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($data->roles->first()->id == $role->id) selected @endif >{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error role"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('user') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/dropify.min.js') }}"></script>

<script>
    $(document).ready(function(){
        var drEvent = $('.dropify').dropify();
    });
</script>
@endsection

