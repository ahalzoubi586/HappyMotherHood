@extends('layouts.auth')
@section('content')
    <div class="row">
        <h2 class="mb-4">المقالات</h2>
        <div class="card">
            <div class="card-header">
                إضافة مقال جديد
            </div>
            <div class="card-body">
                <form id="blogForm">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">التصنيف</span>
                        <select class="form-control" name="category_id">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">عنوان المقال</span>
                        <input type="text" class="form-control" placeholder="عنوان التصنيف" name="title"
                            aria-label="title" aria-describedby="basic-addon1" value="{{ $blog->title }}">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">التفاصيل</span>
                        <textarea id="ckeditor" name="description" class="form-control w-100">{{ $blog->description }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ التعديل</button>
                    <a href="{{ route("blogs.index") }}" class="btn btn-danger">إلغاء</a>
                </form>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#blogForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var description = CKEDITOR.instances['ckeditor'].getData();
                formData.append('description', description); // Append CKEditor content
                $.ajax({
                    url: '{{ route('blogs.update') }}', // Adjust this to your route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            text: 'تم تعديل المقال بنجاح!'
                        }).then((result) => {
                            location.href = '{{ route('blogs.index') }}';
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
        });
    </script>
@stop
