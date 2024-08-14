<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!--   Core JS Files   -->
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>

<script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/ckeditor/ckeditor.js?v=1') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace('ckeditor', {
        contentsLangDirection: 'rtl',
        language: 'ar',
        width: '100%',
        filebrowserUploadUrl:"{{ route('ckeditor.upload',['_token' => csrf_token()]) }}",
    });
</script>
