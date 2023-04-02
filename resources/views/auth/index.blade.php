@extends('_layout.app', [
    "dashboard" => false,
    "title" => "Login"
])

@section('content')
    <div class="auth-page-wrapper pt-5">

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4">
                            <div>
                                <a href="{{ route("auth.login") }}" class="d-inline-block auth-logo">
                                    <img src="{{ env("APP_URL") }}/assets/images/logo-dark.png" alt="" height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Selamat Datang !</h5>
                                    <p class="text-muted">Masuk terlebih dahulu untuk melanjutkan.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form id="submit">

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" autocomplete="off" autofocus placeholder="Masukkan email">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Kata Sandi</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5" name="password" placeholder="Enter password" id="password-input">
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Masuk Sekarang!</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Tidak memiliki akun ? <a href="auth-signup-basic.html" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> {{ env("APP_NAME") }}. Dibuat dengan <i class="mdi mdi-heart text-danger"></i> oleh Mentari Teknologi Digital</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection

@section('javascript')
    <script type="text/javascript">
        class Auth extends App {
            constructor() {
                super()
            }

            csrf_cookie(e) {
                e.preventDefault()

                this.api({
                    url: `sanctum/csrf-cookie`,
                    success: e => {
                        this._login_post()
                    }
                })
            }

            _login_post() {
                let formData = this.formData(`form#submit`)

                this.api({
                    url: `/login_post`,
                    method: `POST`,
                    data: formData,
                    success: e => {
                        console.log(e)
                    },
                    error: err => {
                        console.log(err)
                    }
                })
            }
        }

        var auth = new Auth

        $(document).on(`submit`, `form#submit`, function(e) {
            auth.csrf_cookie(e)
        })
    </script>
@endsection
