@extends('template.app')

@section('meta')
@endsection

@section('title')
Access-Create
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-11 align-self-center">
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <form action="{{ route('access_permission.update') }}" name="form" id="form" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="role" name="role" placeholder="Plese select role">Role</label>
                                <select class="form-control" name="role" id="role" readonly>
                                    <option value="">Select role</option>
                                    @if(isset($roles) && $roles->isNotEmpty())
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($data->id == $role->id) selected @endif>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error role"></span>
                            </div>
                            <input type="checkbox" class="checkboxClass mr-1" id="ckbCheckAll" value=""><span></span><b>Select All</b>
                            <div class="form-group col-sm-12">
                                <label for="permissions" name="permissions" placeholder="Plese select permissions">Permissions</label>
                                <br>
                                <span class="kt-form__help error permissions"></span>
                            </div>
                            @if(isset($permissions) && $permissions->isNotEmpty())
                            @php $abc = true @endphp
                                @foreach($permissions as $permission)
                                    @php if(in_array($permission->id, $data->permissions)){ $abc = true; }else{ $abc = false; }   @endphp
                                    <div class="col-sm-3 mb-1">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" class="custom-control-input checkboxClass" id="{{ $permission->id }}" value="{{ $permission->id }}" @if(in_array($permission->id, $data->permissions)) checked @endif >
                                            <label class="custom-control-label" for="{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                            <a href="{{ route('access_permission') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Cancel</a>
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

        $(document).ready(function () {
            var abc = '<?php echo $abc; ?>';
            if(abc == true){
                $("#ckbCheckAll").prop('checked', true);
            }
            
        });
      
        $("#ckbCheckAll").on('click', function() {
            if (this.checked) {
                $('.checkboxClass').each(function() {
                    $(".checkboxClass").prop('checked', true);
                })
            } else {
                $('.checkboxClass').each(function() {
                    $(".checkboxClass").prop('checked', false);
                })
            }
        });
    </script>
@endsection
