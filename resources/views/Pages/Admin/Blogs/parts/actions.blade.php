<div class="btn-group">
    <a class="btn btn-info" href="{{ route('blogs.edit', ['blog_id' => Crypt::encrypt($id)]) }}">
        <i class="fas fa-edit ms-2"></i>تعديل
    </a>
</div>
