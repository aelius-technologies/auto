@extends('template.app')

@section('meta')
@endsection

@section('title')
    User Update
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
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
                    <form action="{{ route('user.update') }}" name="form" id="form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Plese enter first_name" value="{{ $data->first_name ??'' }}">
                                <span class="kt-form__help error first_name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Plese enter last_name" value="{{ $data->last_name ??'' }}">
                                <span class="kt-form__help error last_name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Plese enter contact number" value="{{ $data->contact_number ??'' }}">
                                <span class="kt-form__help error contact_number"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Plese enter email" value="{{ $data->email ??'' }}">
                                <span class="kt-form__help error email"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Plese enter password">
                                <span class="text-danger">* Leave blank for not update password</span>
                                <span class="kt-form__help error password"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="branch">Branch</label>
                                <select class="form-control" name="branch" id="branch">
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
                                <select class="form-control" name="role" id="role">
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
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
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

<script src="{{ asset('assets/js/promise.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.bundle.js') }}"></script>

<script>
    $(document).ready(function(){
        $("#contact_number").keypress(function(e){
            var keyCode = e.keyCode || e.which;
            var $this = $(this);
            //Regex for Valid Characters i.e. Numbers.
            var regex = new RegExp("^[0-9\b]+$");

            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            // for 10 digit number only
            if ($this.val().length > 9) {
                e.preventDefault();
                return false;
            }
            if (e.charCode < 54 && e.charCode > 47) {
                if ($this.val().length == 0) {
                    e.preventDefault();
                    return false;
                } else {
                    return true;
                }
            }
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
    });
</script>

<script>
    $(document).ready(function () {
        var form = $('#form');
        $('.kt-form__help').html('');
        form.submit(function(e) {
            $('.help-block').html('');
            $('.m-form__help').html('');
            $.ajax({
                url : form.attr('action'),
                type : form.attr('method'),
                data: new FormData($(this)[0]),
                dataType: 'json',
                async: false,
                processData: false,
                contentType: false,
                success : function(json){
                    return true;
                },
                error: function(json){
                    if(json.status === 422) {
                        e.preventDefault();
                        var errors_ = json.responseJSON;
                        $('.kt-form__help').html('');
                        $.each(errors_.errors, function (key, value) {
                            $('.'+key).html(value);
                        });
                    }
                }
            });
        });
    });
</script>
@endsection

