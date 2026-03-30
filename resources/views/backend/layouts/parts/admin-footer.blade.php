<!-- jQuery -->
<script src="{{asset('assets/admin/vendors/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI -->
<script src="{{asset('assets/admin/build/vendors/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

@if(0)
<!-- FastClick -->
<script src="{{asset('assets/admin/vendors/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('assets/admin/vendors/nprogress/nprogress.js')}}"></script>
<!-- bootstrap-progressbar -->
<script src="{{asset('assets/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
@endif

<!-- iCheck -->
<script src="{{asset('assets/admin/vendors/iCheck/icheck.min.js')}}"></script>
<!-- SELECT2 -->
<script src="{{asset('assets/admin/vendors/select2/dist/js/select2.min.js')}}"></script>
<!-- TinyMCE -->
<script src="{{asset('assets/admin/build/vendors/tinymce/tinymce.min.js')}}"></script>
<!-- Alert -->
<script src="{{asset('assets/admin/build/vendors/alertifyjs/alertify.min.js')}}"></script>
<!-- Toastify -->
<script src="{{asset('assets/admin/build/vendors/toastr/js/toastr.min.js') }}"></script>

<!-- Form Validation -->
<script src="{{ asset('assets/admin/vendors/bootstrap-validator/validator.js') }}"></script>

@stack('footerscripts')

<!-- Custom Theme Scripts -->
<!-- <script src="{{url('assets/admin/build/js/custom.min.js')}}"></script> -->
<script src="{{asset('assets/admin/build/js/custom.js')}}"></script>
<script src="{{asset('assets/admin/build/js/pw-custom.js')}}"></script>
