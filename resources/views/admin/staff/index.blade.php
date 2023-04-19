@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Staff"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#action" data-bs-action="create" data-bs-title="Tambah Staff" data-bs-id=""><i class="ri-file-add-line"></i> Tambah</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h6 class="card-title">Data Staff</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed table-bordered table-sm" id="staffs">
                            <thead>
                                <tr>
                                    <th width="20%">Foto</th>
                                    <th width="20%">Nama</th>
                                    <th width="20%">Email</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <x-dashboard.modal modal-id="action">
        <form id="submit">
            <div class="row">
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
                <div class="col-lg-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </x-dashboard.modal>
@endsection

@section('javascript')
    <script type="text/javascript">
        class Staff extends App {
            constructor() {
                super()

                this.staffs
            }

            get() {
                this.staffs = $(`table#staffs`).laravelTable({
                    url: `${ this.baseUrl }/api/admin/staff`,
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.authorization
                    },
                    pagination: {
                        customClass: `pagination-sm`
                    },
                    limit: {
                        customClass: `form-select-sm`
                    },
                    search: {
                        placeholder: `Cari nama...`,
                        customClass: `input-group-sm`
                    },
                    columns: [
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                return `<img src="${ e.picture }" style="width: 100px;" alt="${ e.name }" />`
                            }
                        },
                        {
                            data: `name`
                        },
                        {
                            data: `email`
                        },
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                return `
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-icon update" data-bs-toggle="modal" data-bs-target="#action" data-bs-action="update" data-bs-title="Edit Staff" data-bs-id="${ e.id }"><i class="ri-edit-circle-line"></i></button>
                                        <button type="button" class="btn btn-danger btn-icon destroy" data-id="${ e.id }" data-title="${ e.name }"><i class="ri-delete-bin-6-line"></i></button>
                                    </div>
                                `
                            }
                        }
                    ]
                })
            }

            submit(e) {
                e.preventDefault()

                let formData = this.formData(`form#submit`)

                let action = $(`#action [name=action]`).val()
                let id = $(`#action [name=id]`).val()

                let url = `/api/admin/staff/store`

                console.log(action)

                if (action == `update`) {
                    url = `/api/admin/staff/${ id }/update`
                    formData.append(`_method`, `PUT`)
                }

                $(`#action form#submit .error`).html(``)

                this.api({
                    url: url,
                    method: `POST`,
                    data: formData,
                    success: e => {
                        $(`#action form#submit input`).val(``)

                        this.alertSuccess(`Data berhasil disimpan!`)

                        this.staffs.fresh()

                        $(`#action form#submit [data-bs-dismiss=modal]`).trigger(`click`)
                    },
                    error: err => {
                        let error = err.message

                        $.each(error, function (index, value) {
                            $(`#action form#submit .error#error-${ index }`).html(`* ${ value }`)
                        })
                    }
                })
            }

            show(id) {
                this.api({
                    url: `/api/admin/staff/${ id }/show`,
                    success: e => {
                        let data = e.data

                        $(`#action form#submit [name=name]`).val(data.name)
                        $(`#action form#submit [name=email]`).val(data.email)
                    }
                })
            }

            destroy(slug) {
                this.api({
                    url: `/api/admin/staff/${ slug }/destroy`,
                    method: `DELETE`,
                    success: e => {
                        this.staffs.fresh()

                        this.alertSuccess(`Data has been destroy!`)
                    }
                })
            }
        }

        var staff = new Staff

        staff.get()

        $(document).on(`click`, `#action [data-bs-dismiss=modal]`, function() {
            $(`#action form#submit input`).val(``)
            $(`#action form#submit .error`).html(``)
        })

        $(document).on(`submit`, `#action form#submit`, function(e) {
            staff.submit(e)
        })

        $(document).on(`click`, `button.destroy`, function() {
            let id = $(this).data(`id`)
            let title = $(this).data(`title`)

            app.alertConfirm({
                title: `Are you sure?`,
                text: `To delete the ${ title }.`,
                isConfirmed: e => {
                    staff.destroy(id)
                }
            })
        })

        $(document).on(`click`, `button.update`, function() {
            let id = $(this).data(`bs-id`)

            staff.show(id)
        })
    </script>
@endsection
