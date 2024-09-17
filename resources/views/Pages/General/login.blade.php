@extends('layouts.guest')
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5 d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/momm_logo.png') }}" class="img-fluid mb-3 logo" style="width:25%;"
                    alt="Logo image"/>
                <h1 class="text-center">أمومة سعيدة</h1>
            </div>

            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 text-right">
                <h4>تسجيل الدخول</h4>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div data-mdb-input-init class="form-outline mb-4 d-flex justify-content-center align-items-center">
                        <input type="email" name="email" id="form3Example3"
                            class="form-control form-control-lg text-right" placeholder="البريد الإلكتروني" />
                        <i class="fa fa-at ms-2" style="font-size: 1.5rem"></i>
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-3 d-flex justify-content-center align-items-center">
                        <input type="password" name="password" id="form3Example4"
                            class="form-control form-control-lg text-right" placeholder="كلمة المرور" />
                        <i class="fa fa-key ms-2" style="font-size: 1.5rem"></i>
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

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">تسجيل الدخول</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   @include('layouts.Footers.guest_footer')
</section>