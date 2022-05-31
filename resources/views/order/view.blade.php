@extends('template.app')

@section('meta')
@endsection

@section('title')
Order View
@endsection

@section('styles')
<link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Order View</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('order') }}" class="text-muted">Order</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Order ID</label>
                            <input type="text" name="order_id" id="order_id" class="form-control" value="{{ $data->order_id ?? '' }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Branch Name</label>
                            <input type="text"  name="branch_name" id="branch_name" class="form-control" placeholder="Enter Branch Name" value="{{ $data->branch_name ?? '' }}" readonly>
                        </div>
                    </div>
                    <hr>
                    <br>
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
                    <form role="form"  enctype="multipart/form-data">
                        <div class="row setup-content" id="step-1">
                            <div class="col-lg-12 ">
                                <div class="col-md-12">
                                    <h3> Personal Details</h3>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Booking Date</label>
                                            <input type="date" name="booking_date" id="booking_date" class="form-control" value="{{ $data->booking_date ?? '' }}" disabled>
                                            <span class="kt-form__help error booking_date"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Customer Name</label>
                                            <input type="text"  name="customer_name" id="customer_name" class="form-control" placeholder="Enter Customer Name" value="{{ $data->customer_name ?? '' }}" disabled>
                                            <span class="kt-form__help error customer_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Branch</label>
                                            <select name="branch" id="branch" class="form-control" disabled>
                                                <option value="">Select Branch</option>
                                                @if(isset($branch) && $branch->isNotEmpty())
                                                    @foreach($branch AS $branch)
                                                        <option value="{{ $branch->id }}" @if($data->branch_id == $branch->id) selected @endif >{{ $branch->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error branch"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Company Name</label>
                                            <input type="text"  name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name" value="{{ $data->company_name ?? '' }}" disabled>
                                            <span class="kt-form__help error company_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label mt-3">Address Correspondence</label>
                                            <textarea  name="address_1" id="address_1" class="form-control" placeholder="Enter Address Correspondence" disabled>{{ $data->address ?? '' }}</textarea>
                                            <span class="kt-form__help error address_1"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Registration Correspondence</label>
                                            <span class="btn btn-outline-secondary ml-5 mb-2">&nbsp;&nbsp;&nbsp;<input id="chek_address" type="checkbox" class="form-check-input mr-0" value=""><b>Same AS Above</b></span>
                                            <textarea  name="address_2" id="address_2" class="form-control" placeholder="Enter Registration Correspondence" disabled>{{ $data->registration ?? '' }}</textarea>
                                            <span class="kt-form__help error address_2"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">GST</label>
                                            <input type="text"  name="gst" id="gst" class="form-control" placeholder="Enter GSTIN" value="{{ $data->gst ?? '' }}" disabled>
                                            <span class="kt-form__help error gst"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Email</label>
                                            <input type="email"  name="email" id="email" class="form-control" placeholder="Enter Email Address" value="{{ $data->email ?? '' }}" disabled>
                                            <span class="kt-form__help error email"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Mobile Number</label>
                                            <input type="number"  name="contact_number" id="contact_number" class="form-control" placeholder="Enter Mobile Number" value="{{ $data->contact_number ?? '' }}" disabled>
                                            <span class="kt-form__help error contact_number"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">PAN No.</label>
                                            <input type="number"  name="pan_number" id="pan_number" class="form-control" placeholder="Enter PAN Number" value="{{ $data->pan_number ??'' }}"disabled >
                                            <span class="kt-form__help error pan_number"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Aadhar No.</label>
                                            <input type="number"  name="adhar_number" id="aadhar_number" class="form-control" placeholder="Enter Aadhar Number" value="{{ $data->adhar_number ??'' }}" disabled>
                                            <span class="kt-form__help error aadhar_number"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">License No.</label>
                                            <input type="text"  name="license_number" id="license_number" class="form-control" placeholder="Enter License Number" value="{{ $data->licance_number ??'' }}" disabled>
                                            <span class="kt-form__help error license_number"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Upload PAN</label>
                                            <input type="file" name="pan_image" id="pan_image" class="dropify" data-default-file="{{ $data->pan_image ??'' }}" data-allowed-file-extensions="jpg png jpeg" data-max-file-size-preview="2M" disabled="disabled">
                                            <span class="kt-form__help error pan_image"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Upload Aadhar</label>
                                            <input type="file" name="adhar_image" id="adhar_image" class="dropify disabled" data-default-file="{{ $data->adhar_image ??'' }}" data-allowed-file-extensions="jpg png jpeg" data-max-file-size-preview="2M" disabled>
                                            <span class="kt-form__help error adhar_image"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Upload License</label>
                                            <input type="file" name="licance_image" id="licance_image" class=" dropify disabled" data-default-file="{{ $data->licance_image ??'' }}" data-allowed-file-extensions="jpg png jpeg" data-max-file-size-preview="2M" disabled>
                                            <span class="kt-form__help error licance_image"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Date of Birth</label>
                                            <input type="date"  name="dob" id="dob" class="form-control" value="{{ $data->dob ?? '' }}" disabled>
                                            <span class="kt-form__help error dob"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Nominee Name</label>
                                            <input type="text"  name="nominee_name" id="nominee_name" class="form-control" placeholder="Enter Nominee Name" value="{{ $data->nominee_name ?? '' }}" disabled>
                                            <span class="kt-form__help error nominee_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Nominee Relation</label>
                                            <input type="text"  name="nominee_relation" id="nominee_relation" class="form-control" placeholder="Enter Nominee Relation" value="{{ $data->nominee_reletion ?? ''}}" disabled>
                                            <span class="kt-form__help error nominee_relation"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Nominee Age</label>
                                            <input type="number"  name="nominee_age" id="nominee_age" class="form-control" placeholder="Enter Nominee Age" value="{{ $data->nominee_age ?? '' }}" disabled>
                                            <span class="kt-form__help error nominee_age"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Occupation</label>
                                            <input type="text"  name="occupation" id="occupation" class="form-control" placeholder="Enter Occupation" value="{{ $data->occupation ?? '' }}" disabled>
                                            <span class="kt-form__help error occupation"></span>
                                        </div>
                                    </div>
                                    <a href="{{ route('order') }}">
                                        <button class="btn btn-outline-secondary btn-lg pull-right" type="button">Back</button>
                                    </a>
                                    <button class=" btn waves-effect waves-light btn-rounded btn-outline-primary nextBtn btn-lg pull-right" type="button">Next</button>
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
                                                @if(isset($sales) && $sales->isNotEmpty())
                                                    @foreach($sales AS $row)
                                                        <option value="{{ $row->id }}"  @if($data->sales_person_id == $row->id) selected @endif>{{ $row->name ??'' }}</option>
                                                        @endforeach
                                                @else        
                                                    <option value="1" selected>Super Admin</option>
                                                @endif
                                            </select>
                                            <span class="kt-form__help error sales_person_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Model</label>
                                            <select name="model" id="model" class="form-control" disabled>
                                                <option value="">Select Model</option>
                                                @if(isset($product) && $product->isNotEmpty())
                                                    @foreach($product AS $row)
                                                        <option value="{{ $row->id }}" @if($data->product_id == $row->id) selected @endif>{{ $row->name ??'' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error model"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Variant</label>
                                            <select name="veriant" id="veriant" class="form-control" disabled>
                                                <option value="">Select Variant</option>
                                                @if(isset($product) && $product->isNotEmpty())
                                                    @foreach($product AS $row)
                                                        <option value="{{ $row->id }}" @if($data->product_id == $row->id) selected @endif>{{ $row->veriant ??'' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error variant"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Exterior Color</label>
                                            <input type="text"  name="exterior_color" id="exterior_color" class="form-control" placeholder="Enter Exterior Color" value="{{ $data->exterior_color ?? '' }}" disabled>
                                            <span class="kt-form__help error exterior_color"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Interior Color</label>
                                            <input type="text"  name="interior_color" id="interior_color" class="form-control" placeholder="Enter Interior Color" value="{{ $data->interior_color ?? '' }}" disabled>
                                            <span class="kt-form__help error interior_color"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Standard Ex-Showroom Price</label>
                                            <input type="number"  name="ex_showroom_price" id="ex_showroom_price" class="form-control" placeholder="Enter Standard Ex-Showroom Price" value="{{ $data->ex_showroom_price ?? '' }}" disabled>
                                            <span class="kt-form__help error ex_showroom_price"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Registration Tax</label>
                                            <input type="text"  name="registration_tax" id="registration_tax" class="form-control" placeholder="Enter Registration Tax" value="{{ $taxOne->percentage ?? '' }}" disabled>
                                            <span class="kt-form__help error registration_tax"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Insurance</label>
                                            <select name="insurance" id="insurance" class="form-control"disabled >
                                                <option value="">Select Insurance</option>
                                                @if(isset($insurance) && $insurance->isNotEmpty())
                                                    @foreach($insurance AS $row)
                                                        <option value="{{ $row->id }}"  @if($data->insurance_id == $row->id) selected @endif>{{ $row->name ??'' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error insurance"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Municipal Tax</label>
                                            <input type="text"  name="municipal_tax" id="municipal_tax" class="form-control" placeholder="Enter Municipal Tax" value="{{ $taxTwo->percentage ?? '' }}" disabled>
                                            <span class="kt-form__help error municipal_tax"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">1% for TCS Tax</label>
                                            <input type="text"  name="tcs_tax" id="tcs_tax" class="form-control" placeholder="Enter TCS Tax" value="{{ $taxThree->percentage ?? '' }}" disabled>
                                            <span class="kt-form__help error tcs_tax"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Accessories</label>
                                        
                                            <select name="accessories[]" id="accessories" class="form-control select2 disabled" style="width:100%" multiple disabled>
                                            <option value="">Select Accessory</option>
                                                @if(isset($accessory) && $accessory->isNotEmpty())
                                                <option value="">Select Accessory</option>
                                                    @foreach($accessory AS $row)
                                                        <option value="{{ $row->id }}" @if( in_array($row->id, explode(" ," ,$data->accessory_id))) selected @endif data-price="{{ $row->price }}">{{ $row->name ??'' }} - {{ $row->price }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error accessories"></span>
                                            <span>Accessories Total -</span>
                                            <span>30000</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Fastag</label>
                                            <select name="fastag" id="fastag" class="form-control" disabled >
                                                <option value="">Select FastTag</option>
                                                @if(isset($fasttag) && $fasttag->isNotEmpty())
                                                    @foreach($fasttag AS $row)
                                                        <option value="{{ $row->id }}" @if($data->fasttag_id == $row->id) selected @endif >{{ $row->tag_id ??'' }} - {{ $row->amount }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error fastag"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Trade-In Value (if any)</label>
                                            <select name="trade_in_value" id="trade_in_value" class="form-control" disabled>
                                                <option value="">Select Trad-in-value</option>
                                                @if(isset($car_exchange) && $car_exchange->isNotEmpty())
                                                    @foreach($car_exchange AS $row)
                                                        <option value="{{ $row->id }}" @if($data->trad_in_value == $row->id) selected @endif>{{ $row->product_name ??'' }} - {{ $row->price }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error trade_in_value"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">On Road Price</label>
                                            <input type="number" name="on_road_price" id="on_road_price" class="form-control" placeholder="Enter On Road Price" value="{{ $data->on_road_price ?? '' }}" disabled>
                                            <span class="kt-form__help error on_road_price"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">On Road Price (In Words)</label>
                                            <input type="text" name="on_road_price_word" id="on_road_price_word" class="form-control" placeholder="Enter On Road Price In Words" value="{{ $data->on_road_price_word ?? '' }}" disabled>
                                            <span class="kt-form__help error on_road_price_word"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Extended Warranty</label>
                                            <select name="extended_warranty" id="extended_warranty" class="form-control"disabled >
                                                <option value="">Select Extanded Warranty</option>
                                                @if(isset($extanded_warranty) && $extanded_warranty->isNotEmpty())
                                                    @foreach($extanded_warranty AS $row)
                                                        <option value="{{ $row->id }}" @if($data->extanded_warranty_id == $row->id) selected @endif>{{ $row->years ??'' }} - {{ $row->amount }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error extended_warranty"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Finance Bank Name</label>
                                            <select name="finance_name" id="finance_name" class="form-control" disabled>
                                                <option value="">Select Finance Bank Name</option>
                                                @if(isset($finance) && $finance->isNotEmpty())
                                                    @foreach($finance AS $row)
                                                        <option value="{{ $row->id }}" @if($data->finance_id == $row->id) selected @endif>{{ $row->name ??'' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error finance_bank_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Finance Branch Name</label>
                                            <select name="finance_branch_name" id="finance_branch_name" class="form-control" disabled>
                                                <option value="">Select Finance Branch Name</option>
                                                @if(isset($finance) && $finance->isNotEmpty())
                                                    @foreach($finance AS $row)
                                                        <option value="{{ $row->branch_id }}" @if($data->finance_id == $row->id) selected @endif>{{ $row->branch_name ??'' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error finance_branch_name"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Lead Source</label>
                                            <select name="lead" id="lead" class="form-control" disabled >
                                                <option value="">Select Lead Source</option>
                                                @if(isset($lead) && $lead->isNotEmpty())
                                                    @foreach($lead AS $row)
                                                        <option value="{{ $row->id }}" @if($data->lead_id == $row->id) selected @endif>{{ $row->name ??'' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="kt-form__help error lead_source"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Mode of Payment</label>
                                            <select name="mode_of_payment" id="mode_of_payment" class="form-control" disabled>
                                                <option value="">Select Mode Of Payment</option>
                                                <option value="cash_on_delivery"@if($data->mode_of_payment == 'cash_on_delivery') selected @endif>Cash On Delivery</option>
                                                <option value="online" @if($data->mode_of_payment == 'online') selected @endif>Online/Cradit Card/Debit Card/UPI</option>
                                            </select>
                                            <span class="kt-form__help error mode_of_payment"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Tentative Delivery Date</label>
                                            <input type="date" name="tentative_delivery_date" id="tentative_delivery_date" class="form-control" value="{{ $data->tentative_delivery_date ?? '' }}" disabled>
                                            <span class="kt-form__help error tentative_delivery_date"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Booking Amount</label>
                                            <input type="number"  name="booking_amount" id="booking_amount" class="form-control" placeholder="Enter Booking Amount" value="{{ $data->booking_amount ?? '' }}" disabled>
                                            <span class="kt-form__help error booking_amount"></span>
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
                                    <a href="{{ route('order') }}">
                                        <button class="btn btn-outline-secondary btn-lg pull-right" type="button">Back</button>
                                    </a>
                                    <button class="btn  btn-outline-secondary prevBtn btn-lg pull-left" type="button">Previous</button>
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
<script src="{{ asset('assets/js/promise.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.bundle.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script>
    $(document).ready(function(){
        if($('#address_1').val() == $('#address_2').val()){
            $("#chek_address").attr("checked" ,true);
        }

        var copyData = '';
        $('#chek_address').on('click', function(){
            if($('#chek_address').is(":checked")){
                copyData = $('#address_1').val();
                $('#address_2').val(copyData);
            }else{
                $('#address_2').val('');
            }
        })
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop profile image here or click',
                'remove':  'Remove',
                'error':   'Ooops, something wrong happended.'
            }
        });

        var drEvent = $('.dropify').dropify();

        var dropifyElements = {};
        $('.dropify').each(function () {
            dropifyElements[this.id] = false;
        });

        drEvent.on('dropify.beforeClear', function(event, element){
            id = event.target.id;
            if(!dropifyElements[id]){
                var url = "{!! route('obf.profile.remove') !!}";
                <?php if(isset($data) && isset($data->id)){ ?>
                    var id_encoded = "{{ base64_encode($data->id) }}";

                    Swal.fire({
                        title: 'Are you sure want delete this image?',
                        text: "",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then(function (result){
                        if (result.value){
                            $.ajax({
                                url: url,
                                type: "POST",
                                data:{
                                    id: id_encoded,
                                    _token: "{{ csrf_token() }}"
                                },
                                dataType: "JSON",
                                success: function (data){
                                    if(data.code == 200){
                                        Swal.fire('Deleted!', 'Deleted Successfully.', 'success');
                                        dropifyElements[id] = true;
                                        element.clearElement();
                                    }else{
                                        Swal.fire('', 'Failed to delete', 'error');
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown){
                                    Swal.fire('', 'Failed to delete', 'error');
                                }
                            });
                        }
                    });

                    return false;
                <?php } else { ?>
                    Swal.fire({
                        title: 'Are you sure want delete this image?',
                        text: "",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then(function (result){
                        if (result.value){
                            Swal.fire('Deleted!', 'Deleted Successfully.', 'success');
                            dropifyElements[id] = true;
                            element.clearElement();
                        }else{
                            Swal.fire('Cancelled', 'Discard Last Operation.', 'error');
                        }
                    });
                    return false;
                <?php } ?>
            } else {
                dropifyElements[id] = false;
                return true;
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.select2').select2({
            placeholder:"Select Accessory",
        })
    }); 
     
    $('#accessories').on('change' , function(){
        var sum = 0;
        $('#accessories :selected').each(function() {
            sum  += parseInt($(this).data("price"));
        })
        console.log(sum);
        $('#total').html(sum);
    });
</script>
@endsection