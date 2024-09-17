@extends('layouts.guest')
@section('content')
    <div class="container" dir="rtl">
        <br>
        <h2>حذف حساب</h2>
        <p style="color:red">* لا يمكن التراجع عن هذا الإجراء، وسيتم حذف جميع بياناتك نهائيًا.</p>
        <br>
        <h3>يمكنك من خلال هذا النموذج حذف حسابك نهائياً من جميع قواعد البيانات لدينا</h3>
        <br>
        <h4>نموذج حذف حساب</h4>
        <form action="{{ route('delete-account') }}" method="POST">
            @csrf
            <div data-mdb-input-init class="form-outline mb-4 d-flex justify-content-center align-items-center">
                <input type="email" name="email" value="{{ old('email') }}" id="form3Example3"
                    class="form-control form-control-lg text-right" placeholder="البريد الإلكتروني" />
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-3 d-flex justify-content-center align-items-center">
                <input type="password" name="password" id="form3Example4" class="form-control form-control-lg text-right"
                    placeholder="كلمة المرور" />
            </div>
            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="text-center text-lg-start mt-4 pt-2">
                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                    style="padding-left: 2.5rem; padding-right: 2.5rem;">حذف الحساب</button>
            </div>
        </form>
    </div>
    @include('layouts.Footers.guest_footer')
@stop
