@extends('layouts.auth')
@section('content')
    <div class="row">
        <h2 class="mb-4">التصنيفات</h2>
        <div class="card">
            <div class="card-header">
                إضافة تصنيف جديد
            </div>
            <div class="card-body">
                <form id="categoryForm">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">عنوان التصنيف</span>
                        <input type="text" class="form-control" placeholder="عنوان التصنيف" name="category_title"
                            aria-label="category_title" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">صورة التصنيف</span>
                        <input type="file" class="form-control" name="category_image" accept=".jpg,.png">
                    </div>
                    <div class="text-center">
                        <img id="previewImage" src="{{ asset('asset/img/placeholder.png') }}" style="width:250px" />
                    </div>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </form>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('categories.store') }}', // Adjust this to your route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            text: 'تم إضافة التصنيف بنجاح!'
                        }).then((result) => {
                            location.href = '{{ route('categories.index') }}';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            text: error
                        });
                    }
                });
            });

            // Preview image on selection
            $('input[name="category_image"]').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@stop
