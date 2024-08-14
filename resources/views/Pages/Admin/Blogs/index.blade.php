@extends('layouts.auth')
@section('content')
    <div class="row">
        <h2 class="mb-4">المقالات</h2>
        <div class="card">
            <div class="card-header">
                <a href="{{ route("blogs.create") }}" class="btn btn-primary">إضافة مقال جديد</a>
            </div>
            <div class="card-body">
                <div class="col-lg-12 table-responsive">
                    <table id="myTable" class="table mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>التصنيف</th>
                                <th>عنوان المقال</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الحالة</th>
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
                ajax: "{{ route('blogs.list') }}",
                columns: [{
                        data: 'id',
                    },
                    {
                        data: 'category',
                    },
                    {
                        data: 'title',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'action',
                    },
                ]
            });
        });
    </script>
@stop
