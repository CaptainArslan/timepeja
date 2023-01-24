<title>Registration</title>
<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset ('images/favicon.ico')}}">
<!-- Bootstrap css -->
<link href="{{ asset ('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<!-- App css -->
<link href="{{ asset ('css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
<!-- icons -->
<link href="{{ asset ('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Head js -->
<script src="{{ asset ('js/head.js')}}"></script>


<body class="authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-6">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="#" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset('images/logo-dark.png')}}" alt="" height="22">
                                        </span>
                                    </a>
                                    <a href="#" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset('images/logo-light.png')}}" alt="" height="22">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Don't have an account? Create your account, it takes less than a minute</p>
                                @include('partials.errors_page')
                            </div>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name"  autocomplete="name" autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="user_type" class="form-label">User Type</label>
                                    <input class="form-control" type="text" id="user_type" name="user_type" value="{{ old('user_type') }}" placeholder="Enter your user_type"  autocomplete="user_type" autofocus>
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control" id="emailaddress" name="email" value="{{ old('email') }}"  autocomplete="email">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" placeholder="Enter your password" name="password"  autocomplete="new-password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <input id="password-confirm" type="password" class="form-control" placeholder="Enter your password" name="password_confirmation"  autocomplete="new-password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center d-grid">
                                    <button class="btn btn-success" type="submit"> Sign Up </button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <!-- <p class="text-white-50">Already have account? <a href="{{ route('login')}}" class="text-white ms-1"><b>Sign In</b></a></p> -->
                        </div> <!-- end col -->
                    </div>
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