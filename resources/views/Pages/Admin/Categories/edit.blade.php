@extends('layouts.auth')
@section('content')
    <div class="row">
        <h2 class="mb-4">التصنيفات</h2>
        <div class="card">
            <div class="card-header">
                تعديل تصنيف: (<strong>{{ $category->title }}</strong>)
            </div>
            <div class="card-body">
                <form id="categoryForm">
                    <input type="hidden" name="category_id" value="{{ Crypt::encrypt($category->id) }}" />
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">عنوان التصنيف</span>
                        <input type="text" class="form-control" placeholder="عنوان التصنيف" name="category_title"
                            aria-label="category_title" aria-describedby="basic-addon1" value="{{ $category->title }}">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">صورة التصنيف</span>
                        <input type="file" class="form-control" name="category_image" accept=".jpg,.png">
                    </div>
                    <div class="text-center">
                        <img id="previewImage" src="{{ asset('images/categories/' . $category->image) }}"
                            style="width:250px" />
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">حالة التصنيف</span>
                        <div class="form-control d-flex align-items-center">
                            <input type="checkbox" id="status" switch="primary" name="status" value="1"
                                {{ $category->deleted_at == null ? 'checked' : '' }} />
                            <label for="status" data-on-label="فعال" data-off-label="غير فعال"></label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ التعديل</button>
                    
                    <a href="{{ route("categories.index") }}" class="btn btn-danger">إلغاء</a>
                </form>
            </div>
            <div class="table-responsive">
                <hr>
                <h3>المقالات</h3>
                <table class="table table-stripped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان المقال</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category->blogs as $item)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <th>{{ $item->title }}</th>
                                <th>
                                    <a href="{{ route("blogs.edit",["blog_id" => Crypt::encrypt($item->id)]) }}" class="btn btn-info">
                                        <i class="fas fa-edit ms-2"></i>تعديل
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                    url: '{{ route('categories.update') }}', // Adjust this to your route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            text: 'تم تعديل التصنيف بنجاح!'
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
