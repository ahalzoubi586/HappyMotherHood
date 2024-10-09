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
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon2">رابط الصورة</span>
                        <input type="text" class="form-control" placeholder="رابط الصورة" name="category_image_url"
                            aria-label="category_image_url" aria-describedby="basic-addon2">
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
            // Preview image from file input
            $('input[name="category_image"]').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
                // Clear the URL input
                $('input[name="category_image_url"]').val('');
            });

            // Preview image from URL input
            $('input[name="category_image_url"]').on('input', function() {
                const url = $(this).val();
                if (url) {
                    $('#previewImage').attr('src', url);
                    // Clear the file input
                    $('input[name="category_image"]').val('');
                }
            });

            // Form submission
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                // Handle image URL or file
                if ($('input[name="category_image_url"]').val()) {
                    formData.append('category_image_url', $('input[name="category_image_url"]').val());
                } else if ($('input[name="category_image"]').val()) {
                    formData.append('category_image', $('input[name="category_image"]')[0].files[0]);
                }

                $.ajax({
                    url: '{{ route('categories.store') }}',
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
                            text: 'حدث خطأ ما!'
                        });
                    }
                });
            });
        });
    </script>
@stop
