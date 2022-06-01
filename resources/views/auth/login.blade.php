@extends('auth.template.app')

@section('meta')
@endsection

@section('title')
Login
@endsection

@section('styles')
@endsection

@section('content')

<div class="d-flex justify-content-center">
<div class="row w-50">
    <div class="col-lg-12">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Savan IB Automative - Login!</h1>
            </div>
            <form class="user" method="post" action="{{ route('signin') }}">
                @csrf
                <div class="form-group">
                    <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    @error('email')
                        <div class="invalid-feedback" style="display: block;">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    @error('password')
                        <div class="invalid-feedback" style="display: block;">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="form-group text-center">
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                </button>
            </form>
            
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
@endsection