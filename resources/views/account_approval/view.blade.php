@extends('template.app')

@section('meta')
@endsection

@section('title')
    Account Approval View
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Account Approval</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('account_approval') }}" class="text-muted">Account Approval</a></li>
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
                    <div class="stepwizard col-md-offset-3">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step">
                                <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                <p>Personal Details</p>
                            </div>
                            <div class="stepwizard-step">
                                <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                                <p>Car Details</p>
                            </div>
                        </div>
                    </div>
                    <form role="form" action="" method="post">
                        <div class="row setup-content" id="step-1">
                            <div class="col-lg-12 ">
                                <div class="col-md-12">
                                    <h3> Personal Details</h3>
                                    <div class="row">
                                        
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Booking Date</label>
                                            <input type="date" name="booking_date" id="booking_date" class="form-control" value="{{ $data->booking_date ??'' }}" disabled>
                                            <span class="kt-form__help error booking_date"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Customer Name</label>
                                            <input type="text"  name="customer_name" id="customer_name" class="form-control" placeholder="Enter Customer Name" value="{{ $data->customer_name ??'' }}" disabled>
                                            <span class="kt-form__help error customer_name"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Branch</label>
                                            <select name="branch" id="branch" class="form-control" disabled>
                                                <option value="">Select Branch</option>
                                            </select>
                                            <span class="kt-form__help error branch"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Company Name</label>
                                            <input type="text"  name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name" value="{{ $data->company_name ??'' }}" disabled>
                                            <span class="kt-form__help error company_name"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Address Correspondence</label>
                                            <textarea disabled name="address_1" id="address_1" class="form-control" placeholder="Enter Address Correspondence"></textarea>
                                            <span class="kt-form__help error address_1"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Registration Correspondence</label>
                                            <textarea disabled name="address_2" id="address_2" class="form-control" placeholder="Enter Registration Correspondence"></textarea>
                                            <span class="kt-form__help error address_2"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">GST</label>
                                            <input type="text"  name="gst" id="gst" class="form-control" placeholder="Enter GSTIN" value="{{ $data->gst ??'' }}" disabled>
                                            <span class="kt-form__help error gst"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Email</label>
                                            <input type="email"  name="email" id="email" class="form-control" placeholder="Enter Email Address" value="{{ $data->email ??'' }}" disabled>
                                            <span class="kt-form__help error email"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Mobile Number</label>
                                            <input type="number"  name="mobile_number" id="mobile_number" class="form-control" placeholder="Enter Mobile Number" value="{{ $data->mobile_number ??'' }}" disabled>
                                            <span class="kt-form__help error mobile_number"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">PAN No.</label>
                                            <input type="number"  name="pan_number" id="pan_number" class="form-control" placeholder="Enter PAN Number" value="{{ $data->pan_number ??'' }}" disabled>
                                            <span class="kt-form__help error pan_number"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Upload PAN</label>
                                            <input type="file"  name="upload_pan" id="upload_pan" class="form-control" value="{{ $data->pan_image ??'' }}" disabled>
                                            <span class="kt-form__help error upload_pan"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Aadhar No.</label>
                                            <input type="number"  name="aadhar_number" id="aadhar_number" class="form-control" placeholder="Enter Aadhar Number" value="{{ $data->aadhar_number ??'' }}" disabled>
                                            <span class="kt-form__help error aadhar_number"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Upload Aadhar</label>
                                            <input type="file"  name="upload_aadhar" id="upload_aadhar" class="form-control" value="{{ $data->aadhar_image ??'' }}" disabled>
                                            <span class="kt-form__help error upload_aadhar"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">License No.</label>
                                            <input type="text"  name="license_number" id="license_number" class="form-control" placeholder="Enter License Number" value="{{ $data->license_number ??'' }}" disabled>
                                            <span class="kt-form__help error license_number"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Upload License</label>
                                            <input type="file"  name="upload_license" id="upload_license" class="form-control" value="{{ $data->license_image ??'' }}" disabled>
                                            <span class="kt-form__help error upload_license"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Date of Birth</label>
                                            <input type="date"  name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $data->date_of_birth ??'' }}" disabled>
                                            <span class="kt-form__help error date_of_birth"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Nominee Name</label>
                                            <input type="text"  name="nominee_name" id="nominee_name" class="form-control" placeholder="Enter Nominee Name" value="{{ $data->nominee_name ??'' }}" disabled>
                                            <span class="kt-form__help error nominee_name"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Nominee Relation</label>
                                            <input type="text"  name="nominee_relation" id="nominee_relation" class="form-control" placeholder="Enter Nominee Relation" value="{{ $data->nominee_relation ??'' }}" disabled>
                                            <span class="kt-form__help error nominee_relation"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Nominee Age</label>
                                            <input type="number"  name="nominee_age" id="nominee_age" class="form-control" placeholder="Enter Nominee Age" value="{{ $data->nominee_age ??'' }}" disabled>
                                            <span class="kt-form__help error nominee_age"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label">Occupation</label>
                                            <input type="text"  name="occupation" id="occupation" class="form-control" placeholder="Enter Occupation" value="{{ $data->occupation ??'' }}" disabled>
                                            <span class="kt-form__help error occupation"></span>
                                        </div>
                                      
                                    </div>
                                    <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="row setup-content" id="step-2">
                            <div class="col-lg-12 ">
                                <div class="col-md-12">
                                    <h3> Car Details</h3>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Sales Person Name</label>
                                            <select name="sales_person_name" id="sales_person_name" class="form-control" disabled>
                                                <option value="">Select Sales Person</option>
                                            </select>
                                            <span class="kt-form__help error sales_person_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Model</label>
                                            <select name="model" id="model" class="form-control" disabled>
                                                <option value="">Select Model</option>
                                            </select>
                                            <span class="kt-form__help error model"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Variant</label>
                                            <select name="variant" id="variant" class="form-control" disabled>
                                                <option value="">Select Variant</option>
                                            </select>
                                            <span class="kt-form__help error variant"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Exterior Color</label>
                                            <input type="text"  name="exterior_color" id="exterior_color" class="form-control" placeholder="Enter Exterior Color" value="{{ $data->exterior_color ??'' }}" disabled>
                                            <span class="kt-form__help error exterior_color"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Interior Color</label>
                                            <input type="text"  name="interior_color" id="interior_color" class="form-control" placeholder="Enter Interior Color" value="{{ $data->interior_color ??'' }}" disabled>
                                            <span class="kt-form__help error interior_color"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Standard Ex-Showroom Price</label>
                                            <input type="number"  name="standard_exshowroom_price" id="standard_exshowroom_price" class="form-control" placeholder="Enter Standard Ex-Showroom Price" value="{{ $data->standard_exshowroom_price ??'' }}" disabled>
                                            <span class="kt-form__help error standard_exshowroom_price"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Registration Tax</label>
                                            <input type="number"  name="registration_tax" id="registration_tax" class="form-control" placeholder="Enter Registration Tax" value="{{ $data->registration_tax ??'' }}" disabled>
                                            <span class="kt-form__help error registration_tax"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Insurance</label>
                                            <select name="insurance" id="insurance" class="form-control" disabled>
                                                <option value="">Select Insurance</option>
                                            </select>
                                            <span class="kt-form__help error insurance"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Municipal Tax</label>
                                            <input type="number"  name="municipal_tax" id="municipal_tax" class="form-control" placeholder="Enter Municipal Tax" value="{{ $data->municipal_tax ??'' }}" disabled>
                                            <span class="kt-form__help error municipal_tax"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">1% for TCS Tax</label>
                                            <input type="number"  name="tcs_tax" id="tcs_tax" class="form-control" placeholder="Enter TCS Tax" value="{{ $data->tcs_tax ??'' }}" disabled>
                                            <span class="kt-form__help error tcs_tax"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Accessories</label>
                                            <select name="accessories" id="accessories" class="form-control" disabled>
                                                <option value="">Select Accessories</option>
                                            </select>
                                            <span class="kt-form__help error accessories"></span>
                                            <span>Accessories Total -</span>
                                            <span>30000</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Fastag</label>
                                            <input type="text"  name="fastag" id="fastag" class="form-control" placeholder="Enter Fastag" value="{{ $data->fastag ??'' }}" disabled>
                                            <span class="kt-form__help error fastag"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Trade-In Value (if any)</label>
                                            <select name="trade_in_value" id="trade_in_value" class="form-control" disabled>
                                                <option value="">Select Trade-In Value</option>
                                            </select>
                                            <span class="kt-form__help error trade_in_value"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">On Road Price</label>
                                            <input type="number" name="on_road_price" id="on_road_price" class="form-control" placeholder="Enter On Road Price" value="{{ $data->on_road_price ??'' }}" disabled>
                                            <span class="kt-form__help error on_road_price"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">On Road Price (In Words)</label>
                                            <input type="text" name="on_road_price_in_words" id="on_road_price_in_words" class="form-control" placeholder="Enter On Road Price In Words" disabled>
                                            <span class="kt-form__help error on_road_price_in_words"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Extended Warranty</label>
                                            <select name="extended_warranty" id="extended_warranty" class="form-control" disabled>
                                                <option value="">Select Extended Warranty</option>
                                            </select>
                                            <span class="kt-form__help error extended_warranty"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Finance Bank Name</label>
                                            <select name="finance_bank_name" id="finance_bank_name" class="form-control" disabled>
                                                <option value="">Select Finance Bank Name</option>
                                            </select>
                                            <span class="kt-form__help error finance_bank_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Finance Branch Name</label>
                                            <select name="finance_branch_name" id="finance_branch_name" class="form-control" disabled>
                                                <option value="">Select Finance Branch Name</option>
                                            </select>
                                            <span class="kt-form__help error finance_branch_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Lead Source</label>
                                            <select name="lead_source" id="lead_source" class="form-control" disabled>
                                                <option value="">Select Lead Source</option>
                                            </select>
                                            <span class="kt-form__help error lead_source"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Mode of Payment</label>
                                            <select name="mode_of_payment" id="mode_of_payment" class="form-control" disabled>
                                                <option value="">Select Mode of Payment</option>
                                            </select>
                                            <span class="kt-form__help error mode_of_payment"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Tentative Delivery Date</label>
                                            <input type="date" name="tentative_delivery_date" id="tentative_delivery_date" class="form-control" value="{{ $data->tentative_delivery_date ??'' }}" disabled>
                                            <span class="kt-form__help error tentative_delivery_date"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Booking Amount</label>
                                            <input type="number"  name="booing_amount" id="booing_amount" class="form-control" placeholder="Enter Booking Amount" value="{{ $data->booking_amount ??'' }}" disabled>
                                            <span class="kt-form__help error booing_amount"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">My Convinience Program</label>
                                            <input type="checkbox" name="convinience_program">
                                            <select name="convinience_program" id="convinience_program" class="form-control" disabled>
                                                <option value="">Select Convinience Program</option>
                                            </select>
                                            <span class="kt-form__help error convinience_program"></span>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary prevBtn btn-lg pull-left" type="button">Previous</button>
                                </div>
                            </div>
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

