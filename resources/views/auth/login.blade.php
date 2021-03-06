<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>โปรแกรม บริษัท ยูนิเวิร์สโค้ดดิ้งแอนด์ดีไซน์ จำกัด</title>
        <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/colors/blue.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
    </head>
    <body>
        <style>
            /*ใส่ฟ้อน*/
            body ,h1, h2, h3, h4, h5, h6,p{
                background: #fff;
                font-family: 'Sarabun' !important;
                margin: 0;
                overflow-x: hidden;
                color: #54667a;
                font-weight: 300; 
            }

            .rc-anchor-light.rc-anchor-normal {
                border: 1px solid #ffffff !important;
            }

            .rc-anchor-light {
                background: #ffffff !important;
                color: #000 !important;
            }
            .rc-anchor {
                border-radius: 3px;
                box-shadow: 0 0 4px 1px rgb(0 0 0 / 8%);
                -webkit-box-shadow: 0 0 4px 1px rgb(255 255 255 / 8%);
                -moz-box-shadow: 0 0 4px 1px rgba(0,0,0,0.08);
            }

            .card {
                position: relative;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-direction: column;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-color: #fff;
                background-clip: border-box;
                border: 1px solid #e8e8e8 !important;
                border-radius: 10px !important;
            }
            .recaptcha-checkbox-border {
                -webkit-border-radius: 2px;
                -moz-border-radius: 2px;
                border-radius: 2px;
                background-color: #fff;
                border: 2px solid #13c0c8 !important;
                font-size: 1px;
                height: 24px;
                position: absolute;
                width: 24px;
                border-radius: 25px !important;
                z-index: 1;
            }
        </style>
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
        <section id="wrapper">
            <div class="login-register">        
                <div class="login-box card">
                    <div class="card-body">
                        <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('login') }}">
                            <h3 class="box-title mb-3 text-center">เข้าสู่ระบบ</h3>
                            @csrf
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="email" name="email" required="" placeholder="อีเมล" value=""> 
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input class="form-control" type="password" name="password" required="" placeholder="รหัสผ่าน" value="">
                                </div>
                            </div>
                            <div class="form-group " style="margin: auto;">
                                {!! NoCaptcha::renderJs() !!}
                                <div class="g-recaptcha" data-sitekey="6LfzkiMUAAAAAMGEgHr8A44hnG0xh_jTwS7AGOQy"></div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <div class="col-xs-12">
                                    <button class="btn btn-info btn-block text-uppercase waves-effect waves-light" type="submit">ล็อกอิน</button>
                                </div>
                            </div>
                            @error('email')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('password')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('g-recaptcha-response')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/popper.min.js')}}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('js/jquery.slimscroll.js')}}"></script>
        <script src="{{ asset('js/waves.js')}}"></script>
        <script src="{{ asset('js/sidebarmenu.js')}}"></script>
        <script src="{{ asset('/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
        <script src="{{ asset('js/custom.min.js')}}"></script>
        <script src="{{ asset('plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    </body>
</html>
