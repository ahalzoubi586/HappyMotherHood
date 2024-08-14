@extends('layouts.auth')
@section('content')
    <div class="row">
        <h2 class="mb-4">المستخدمين</h2>
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="col-lg-12 table-responsive">
                    <table id="myTable" class="table mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الإسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>مدة تسجيل الدخول</th>
                                <th>تاريخ التسجيل</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        $(function() {
            var table = $('#myTable').DataTable({
                "aLengthMenu": [
                    [5, 10, 25, -1],
                    [5, 10, 25, "All"]
                ],
                "iDisplayLength": 10,
                "language": {
                    "sProcessing": "جارٍ التحميل...",
                    "sLengthMenu": "أظهر _MENU_ مدخلات",
                    "sZeroRecords": "لم يعثر على أية سجلات",
                    "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                    "sInfoPostFix": "",
                    "sSearch": "ابحث:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sPrevious": "<",
                        "sNext": ">",
                        "sLast": ">>"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.list') }}",
                columns: [{
                        data: 'id',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'phone_number',
                    },
                    {
                        data: 'duration',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'action',
                    },
                ]
            });
        });
        $(document).on('click', ".details", function () {
            var user_id = $(this).data('user-id');
            // AJAX request
            $.ajax({
                url: '{{ route("users.details") }}',
                type: 'post',
                data: {id: user_id, '_token': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    // Add response in Modal body
                    $('#defaultModal .modal-body').html(response);
                    // Display Modal
                    $('#defaultModal').modal('show');
                }
            });
        });
    </script>
@stop
@section("modals")

@stop