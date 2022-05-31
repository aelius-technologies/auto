@extends('layouts.app')

@section('meta')
@endsection

@section('title')
Settings
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
@php
$tab = 'general';

if(\Session::has('tab'))
$tab = \Session::get('tab');
@endphp
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Settings</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('settings') }}" class="text-muted">Settings</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Update</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right"></div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a class="nav-link @if($tab == 'general') active @endif" href="#general" data-toggle="tab" aria-expanded="true">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($tab == 'smtp') active @endif" href="#smtp" data-toggle="tab" aria-expanded="false">SMTP</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($tab == 'sms') active @endif" href="#sms" data-toggle="tab" aria-expanded="false">SMS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($tab == 'social') active @endif" href="#social" data-toggle="tab" aria-expanded="false">Social</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($tab == 'logo') active @endif" href="#logo" data-toggle="tab" aria-expanded="false">Logo</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane @if($tab == 'general') active show @else fade @endif" id="general" aria-expanded="true">
                            <form action="{{ route('settings.update') }}" method="post">
                                <div class="row m-2">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="tab" value="general">
                                    @if(isset($general) && $general->isNotEmpty())
                                        @foreach($general as $row)
                                        <div class="form-group col-sm-6">
                                            <label><b>{{ strtoupper(str_replace('_', ' ', $row->key)) }}</b></label>
                                            <input type="text" name="{{ $row->id }}" class="form-control" value="{{ $row->value }}" placeholder="{{ strtoupper(str_replace('_', ' ', $row->key)) }}" />
                                        </div>
                                        @endforeach
                                    @endif
                                    <input type="submit" value="Save" class="btn waves-effect waves-light btn-rounded btn-outline-primary mb-3 ml-3">
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane @if($tab == 'smtp') active show @else fade @endif" id="smtp" aria-expanded="false">
                            <form action="{{ route('settings.update') }}" method="post">
                                <div class="row m-2">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="tab" value="smtp">
                                    @if(isset($smtp) && $smtp->isNotEmpty())
                                        @foreach($smtp as $row)
                                        <div class="form-group col-sm-6">
                                            <label><b>{{ strtoupper(str_replace('_', ' ', $row->key)) }}</b></label>
                                            <input type="text" name="{{ $row->id }}" class="form-control" value="{{ $row->value }}" placeholder="{{ strtoupper(str_replace('_', ' ', $row->key)) }}" />
                                        </div>
                                        @endforeach
                                    @endif
                                    <input type="submit" value="Save" class="btn waves-effect waves-light btn-rounded btn-outline-primary mb-3 ml-3">
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane @if($tab == 'sms') active show @else fade @endif" id="sms" aria-expanded="false">
                            <form action="{{ route('settings.update') }}" method="post">
                                <div class="row m-2">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="tab" value="sms">
                                    @if(isset($sms) && $sms->isNotEmpty())
                                        @foreach($sms as $row)
                                        <div class="form-group col-sm-6">
                                            <label><b>{{ strtoupper(str_replace('_', ' ', $row->key)) }}</b></label>
                                            <input type="text" name="{{ $row->id }}" class="form-control" value="{{ $row->value }}" placeholder="{{ strtoupper(str_replace('_', ' ', $row->key)) }}" />
                                        </div>
                                        @endforeach
                                    @endif
                                    <input type="submit" value="Save" class="btn waves-effect waves-light btn-rounded btn-outline-primary mb-3 ml-3">
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane @if($tab == 'social') active show @else fade @endif" id="social" aria-expanded="false">
                            <form action="{{ route('settings.update') }}" method="post">
                                <div class="row m-2">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="tab" value="social">
                                    @if(isset($social) && $social->isNotEmpty())
                                        @foreach($social as $row)
                                        <div class="form-group col-sm-6">
                                            <label><b>{{ strtoupper(str_replace('_', ' ', $row->key)) }}</b></label>
                                            <input type="text" name="{{ $row->id }}" class="form-control" value="{{ $row->value }}" placeholder="{{ strtoupper(str_replace('_', ' ', $row->key)) }}" />
                                        </div>
                                        @endforeach
                                    @endif
                                    <input type="submit" value="Save" class="btn waves-effect waves-light btn-rounded btn-outline-primary mb-3 ml-3">
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane @if($tab == 'logo') active show @else fade @endif" id="logo" aria-expanded="false">
                            <form action="{{ route('settings.update.logo') }}" method="post" enctype="multipart/form-data">
                                <div class="row m-2">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="tab" value="logo">
                                    @if(isset($logo) && $sms->isNotEmpty())
                                        @foreach($logo as $row)
                                        <div class="form-group col-sm-12">
                                            <label><b>{{ strtoupper(str_replace('_', ' ', $row->key)) }}</b></label>
                                            <input type="file" class="form-control dropify" id="{{ $row->key }}" name="{{ $row->key }}" data-default-file="{{ url('uploads/logo').'/'.$row->value ?? '' }}" data-show-remove="false" data-height="200" data-max-file-size="3M" data-show-errors="true" data-allowed-file-extensions="jpg png jpeg JPG PNG JPEG" data-max-file-size-preview="3M" />
                                            <span class="kt-form__help error {{ $row->key }}"></span>
                                        </div>
                                        @endforeach
                                    @endif
                                    <input type="submit" value="Save" class="btn waves-effect waves-light btn-rounded btn-outline-primary mb-3 ml-3">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/promise.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.bundle.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop profile image here or click',
                'remove': 'Remove',
                'error': 'Ooops, something wrong happended.'
            }
        });

        var drEvent = $('.dropify').dropify();
    });
</script>
@endsection