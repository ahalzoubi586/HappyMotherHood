<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.styles')
    @include('layouts.meta_tags')
    <title>أمومة سعيدة</title>
    <style>
        
        *:not(i){
            font-family: "Almarai", sans-serif !important;
        }
        .h-custom {
            height: calc(100% - 73px);
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
        
label {
	color: #999;
	font-size: 18px;
	font-weight: normal;
	position: absolute;
	pointer-events: none;
	left: 5px;
	top: 10px;
	transition: 0.2s ease all;
	-moz-transition: 0.2s ease all;
	-webkit-transition: 0.2s ease all;
}

input:focus {
	outline: none;
}

input:focus~label,
input:valid~label {
	top: -20px;
	font-size: 14px;
	color: #5264AE;
}

    </style>
</head>

<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5 d-flex flex-column align-items-center">
                    <img src="{{ asset('assets/img/momm_logo.png') }}" class="img-fluid mb-3" alt="Sample image">
                    <h1 class="text-center">أمومة سعيدة</h1>
                </div>
                
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 text-right">
                    <h4>تسجيل الدخول</h4>
                    <form action="{{ route("login") }}" method="POST">
@csrf
                        <div data-mdb-input-init class="form-outline mb-4 d-flex justify-content-center align-items-center">
                            <input type="email" name="email" id="form3Example3" class="form-control form-control-lg text-right"
                                placeholder="البريد الإلكتروني" />
                            <i class="fa fa-at ms-2" style="font-size: 1.5rem"></i>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-3 d-flex justify-content-center align-items-center">
                            <input type="password" name="password" id="form3Example4" class="form-control form-control-lg text-right"
                                placeholder="كلمة المرور" />
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
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">تسجيل الدخول</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <!-- Copyright -->

            <!-- Right -->
            <div>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#!" class="text-white">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            <div class="text-white mb-3 mb-md-0">
                جميع الحقوق محفوظة © 2024
            </div>
            <!-- Right -->
        </div>
    </section>
</body>

</html>
