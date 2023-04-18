@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Pengaturan Profil"
])

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Pengaturan Profil</h5>
                </div>
                <div class="card-body">
                    <form id="submit">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="picture" class="form-label">Foto</label>
                                    <input type="file" name="picture" id="picture" class="form-control picture">
                                    <small class="text-danger error" id="error-picture"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" name="name" id="name" class="form-control name" autocomplete="off" placeholder="Nama">
                                    <small class="text-danger error" id="error-name"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" name="email" id="email" class="form-control email" autocomplete="off" placeholder="Email">
                                    <small class="text-danger error" id="error-email"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control password" autocomplete="off" placeholder="Password">
                                    <small class="text-danger error" id="error-password"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3 d-grid gap-2">
                                <button type="submit" class="btn btn-success"><i class="ri-save-3-line"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        class Profile extends App {
            constructor() {
                super()
            }

            get() {
                this.api({
                    url: `/api/auth/show`,
                    success: e => {
                        let data = e.data

                        $(`form#submit [name=name]`).val(data.name)
                        $(`form#submit [name=email]`).val(data.email)
                        $(`form#submit [name=password]`).val(``)
                        $(`form#submit [name=picture]`).val(``)
                    }
                })
            }

            submit(e) {
                e.preventDefault()

                let formData = this.formData(`form#submit`)
                formData.append(`_method`, `PUT`)

                $(`form#submit .error`).html(``)

                this.api({
                    url: `/api/admin/update_profile`,
                    method: `POST`,
                    content_type: `multipart/form-data`,
                    data: formData,
                    success: e => {
                        this.alertSuccess(`Data berhasil diperbarui!`)

                        $(`form#submit .error`).html(``)
                        main.showAccount()
                        this.get()
                    },
                    error: err => {
                        let error = err.message

                        $.each(error, function (index, value) {
                            $(`form#submit .error#error-${ index }`).html(`* ${ value }`)
                        })
                    }
                })
            }
        }

        var profile = new Profile

        profile.get()

        $(document).on(`submit`, `form#submit`, function(e) {
            profile.submit(e)
        })
    </script>
@endsection
