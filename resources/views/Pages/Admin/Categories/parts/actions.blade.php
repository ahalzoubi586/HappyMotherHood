<div class="btn-group" dir="ltr">
    <a class="btn btn-primary" href="{{ route('blogs.create', ['category_id' => Crypt::encrypt($id)]) }}">
        <span>إضافة مقال</span><i class="fas fa-list ms-2"></i>
    </a>
    <a class="btn btn-info" href="{{ route('categories.edit', ['category_id' => Crypt::encrypt($id)]) }}">
       <span>تعديل</span><i class="fas fa-edit ms-2"></i>
    </a>
</div>
