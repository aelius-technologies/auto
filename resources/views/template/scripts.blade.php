<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/fontawesome-free/js/all.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/wizard.js') }}" type="text/javascript"></script>
<script>
    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-top-left",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "2000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    
    @php
        $success = '';
        if(\Session::has('success'))
            $success = \Session::get('success');

        $error = '';
        if(\Session::has('error'))
            $error = \Session::get('error');
    @endphp

    var success = "{{ $success }}";
    var error = "{{ $error }}";

    if(success != ''){
        toastr.success(success, 'Success');
        // Session::forget('Success');
        // \Session::flash('success');
    }

    if(error != ''){
        toastr.error(error, 'error');
        // $request->session()->flash('error');
        // \Session::flash('error');
    }

    @php
        $success = '';
        if(\Session::has('success'))
            \Session::forget('success');

        $error = '';
        if(\Session::has('error'))
            \Session::forget('error');
    @endphp
</script>
@yield('scripts')