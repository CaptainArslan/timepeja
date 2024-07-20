<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <!-- <title>Dashboard | UBold - Responsive Admin Dashboard Template</title> -->
    <title>@yield('title') | timepejao</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <!-- Plugins css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('css/common.css') }}" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Head js -->
    <script src="{{ asset('js/head.js') }}"></script>

    <!-- toast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    @yield('page_css')
</head>
<!-- body start -->

<body data-layout-mode="default" data-theme="light" data-topbar-color="dark" data-menu-position="fixed"
    data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='false'>
    <!-- Begin page -->
    <div id="wrapper">
        @include('partials.header')
        @include('partials.left-sidebar')
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                @includeif('partials.flash_message')
                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- container -->
            </div> <!-- content -->
            <!-- Footer Start -->
            @include('partials.footer')
            <!-- end Footer -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    @include('.partials.right-sidebar')
    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>
    <script src="https://cdn.socket.io/4.7.4/socket.io.min.js"
        integrity="sha384-Gr6Lu2Ajx28mzwyVR8CFkULdCU7kMlZ9UthllibdOSo6qAiN+yXNHqtgdTvFXMT4" crossorigin="anonymous">
    </script>
    <!-- Vendor js -->
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <!-- Plugins js-->
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script> -->
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <!-- App js-->
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>

    <!-- Plugins js -->
    <script src="{{ asset('/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/libs/autonumeric/autoNumeric.min.js') }}"></script>

    <!-- Init js-->
    <script src="{{ asset('/js/pages/form-masks.init.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

    <!-- firebase cdns -->
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
    <!-- https://cdnjs.cloudflare.com/ajax/libs/firebase/9.19.1/firebase-app.js -->

    {{-- <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/socket.js') }}"></script> --}}
    

    @yield('page_js')

</body>

</html>
