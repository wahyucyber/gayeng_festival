
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Apr 2022 12:54:23 GMT -->
<head>

        <meta charset="utf-8" />
        <title>Sign In | {{ env("APP_NAME") }} - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
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

        @yield('css')

    </head>

    <body>

        @yield('content')

        <!-- JAVASCRIPT -->
        <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js" integrity="sha256-a2yjHM4jnF9f54xUQakjZGaqYs/V1CYvWpoqZzC2/Bw=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/node-waves/waves.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/libs/feather-icons/feather.min.js"></script>
        <script src="{{ env("APP_URL") }}/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="{{ env("APP_URL") }}/assets/js/plugins.js"></script>

        <!-- particles js -->
        <script src="{{ env("APP_URL") }}/assets/libs/particles.js/particles.js"></script>
        <!-- particles app js -->
        <script src="{{ env("APP_URL") }}/assets/js/pages/particles.app.js"></script>
        <!-- password-addon init -->
        <script src="{{ env("APP_URL") }}/assets/js/pages/password-addon.init.js"></script>

        @yield('javascript')
    </body>


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Apr 2022 12:54:24 GMT -->
</html>
