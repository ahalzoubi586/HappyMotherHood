<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.styles')
    @include('layouts.meta_tags')
    @yield('styles')
    <title>أمومة سعيدة</title>
    <style>
        *:not(i) {
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
    @yield('content')
    @yield('scripts')
</body>

</html>
