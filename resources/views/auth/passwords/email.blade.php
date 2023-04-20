<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico')}}">
    <!-- Bootstrap css -->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- icons -->
    <link href="{{ asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Head js -->
    <script src="{{ asset('js/head.js')}}"></script>

</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-6">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="#" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{ asset('images/logo-dark.png')}}" alt="" height="22">
                                        </span>
                                    </a>
                                    <a href="#" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{ asset('images/logo-light.png')}}" alt="" height="22">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Enter your email address for to reset your password</p>
                                @include('partials.errors_page')
                            </div>
                            <form action="{{ route('password.email') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Enter your Email</label>
                                    <input class="form-control" type="email" id="email" name="email" required="required" value="{{ old('email') }}" placeholder="Enter your email">
                                </div>
                                <div class="text-center d-grid">
                                    <button type="submit" class="btn btn-primary" type="submit"> {{ __('Send Password Reset Link') }} </button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <!-- end row -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->
    <!-- Vendor js -->
    <script src="{{ asset('js/vendor.min.js')}}"></script>
    <!-- App js -->
    <script src="{{ asset('js/app.min.js')}}"></script>
</body>

</html>