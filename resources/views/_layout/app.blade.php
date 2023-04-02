
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Apr 2022 12:54:23 GMT -->
<head>

        <meta charset="utf-8" />
        <title>{{ $title }} | {{ env("APP_NAME") }} - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta content="{{ env("APP_URL") }}" name="baseUrl">
        <meta content="Bearer {{ Session::get("authorization") }}" name="authorization">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ env("APP_URL") }}/assets/images/favicon.ico">

        <!-- Layout config Js -->
        <script src="{{ env("APP_URL") }}/assets/js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="{{ env("APP_URL") }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ env("APP_URL") }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ env("APP_URL") }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="{{ env("APP_URL") }}/assets/css/custom.min.css" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert css-->
        <link href="{{ env("APP_URL") }}/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        @yield('css')

    </head>

    <body>

        @if ($dashboard == true)
            <!-- Begin page -->
            <div id="layout-wrapper">

                <header id="page-topbar">
                    <x-dashboard.header />
                </header>

                <!-- ========== App Menu ========== -->
                <div class="app-menu navbar-menu">
                    <x-dashboard.sidebar />
                </div>
                <!-- Left Sidebar End -->
                <!-- Vertical Overlay-->
                <div class="vertical-overlay"></div>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">

                    <div class="page-content">
                        <div class="container-fluid">

                            <!-- start page title -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0">Starter</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                                <li class="breadcrumb-item active">Starter</li>
                                            </ol>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- end page title -->

                            <div class="container">
                                @yield('content')
                            </div>

                        </div>
                        <!-- container-fluid -->
                    </div>
                    <!-- End Page-content -->

                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6">
                                    <script>document.write(new Date().getFullYear())</script> © Velzon.
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-sm-end d-none d-sm-block">
                                        Design & Develop by Themesbrand
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
                <!-- end main content-->

            </div>
            <!-- END layout-wrapper -->



            <!--start back-to-top-->
            <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
                <i class="ri-arrow-up-line"></i>
            </button>
            <!--end back-to-top-->
        @else
            @yield('content')
        @endif

        <!-- JAVASCRIPT -->
        <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js" integrity="sha256-a2yjHM4jnF9f54xUQakjZGaqYs/V1CYvWpoqZzC2/Bw=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/node-waves/waves.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/feather-icons/feather.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="{{ env("APP_URL") }}/assets/js/plugins.js"></script>

        <!-- App js -->
        <script src="{{ env("APP_URL") }}/assets/js/app.js"></script>

        <!-- Sweet Alerts js -->
        <script src="{{ env("APP_URL") }}/assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <!-- particles js -->
        <script src="{{ env("APP_URL") }}/assets/libs/particles.js/particles.js"></script>
        <!-- particles app js -->
        <script src="{{ env("APP_URL") }}/assets/js/pages/particles.app.js"></script>
        <!-- password-addon init -->
        <script src="{{ env("APP_URL") }}/assets/js/pages/password-addon.init.js"></script>

        <script src="{{ env("APP_URL") }}/assets/js/main.js"></script>

        @yield('javascript')
    </body>


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Apr 2022 12:54:24 GMT -->
</html>
