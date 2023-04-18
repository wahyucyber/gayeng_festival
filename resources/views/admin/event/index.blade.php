@extends('_layout.app', [
    'dashboard' => true,
    'title' => "Event"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-end gap-2 mb-3">
            <a href="{{ route("admin.event.create") }}" class="btn btn-success"><i class="ri-file-add-line"></i> Tambah</a>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Data Acara</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed table-bordered table-sm" id="events">
                            <thead>
                                <tr>
                                    <th width="20%">Foto</th>
                                    <th width="60%">Item</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>Foto</th>
                                    <th>Item</th>
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

@section('javascript')
    <script type="text/javascript">
        class Event extends App {
            constructor() {
                super()

                this.events

                this.get()
            }

            get() {
                this.events = $(`table#events`).laravelTable({
                    url: `${ this.baseUrl }/api/admin/event`,
                    headers: {
                        'Authorization': this.authorization,
                        'Accept': 'application/json'
                    },
                    pagination: {
                        customClass: `pagination-sm`
                    },
                    limit: {
                        customClass: `form-select-sm`
                    },
                    search: {
                        placeholder: `Cari judul...`,
                        customClass: `input-group-sm`
                    },
                    columns: [
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                return `<img src="${ e.picture }" style="width: 100%;" alt="${ e.title }" />`
                            }
                        },
                        {
                            data: `title`,
                            html: e => {
                                return `
                                    <div class="row p-0">
                                        <div class="col-lg-6 mb-1">
                                            <h6 class="m-0 d-flex align-items-center gap-1 text-dark"><i class="ri-film-line"></i> Nama</h6>
                                            <small>${ e.title }</small>
                                        </div>
                                        <div class="col-lg-6 mb-1">
                                            <h6 class="m-0 d-flex align-items-center gap-1 text-dark"><i class="ri-git-merge-line"></i> Kategori</h6>
                                            <small>${ e.category != null ? e.category.name : `-` }</small>
                                        </div>
                                        <div class="col-lg-12 mb-1">
                                            <h6 class="m-0 d-flex align-items-center gap-1 text-dark"><i class="ri-calendar-event-fill"></i> Tanggal</h6>
                                            <small>${ app.dateTimeFormat(e.start_time) } - ${ app.dateTimeFormat(e.end_time) }</small>
                                        </div>
                                        <div class="col-lg-12 mb-1">
                                            <h6 class="m-0 d-flex align-items-center gap-1 text-dark"><i class="ri-map-pin-line"></i> Lokasi</h6>
                                            <small>${ e.location }</small>
                                        </div>
                                    </div>
                                `
                            }
                        },
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                return `
                                    <div class="btn-group">
                                        <a href="{{ env("APP_URL") }}/admin/event/${ e.slug }/show" class="btn btn-primary btn-icon"><i class="ri-eye-line"></i></a>
                                        <button type="button" class="btn btn-danger btn-icon destroy" data-id="${ e.slug }" data-title="${ e.title }"><i class="ri-delete-bin-6-line"></i></button>
                                    </div>
                                `
                            }
                        }
                    ]
                })
            }

            destroy(slug) {
                this.api({
                    url: `/api/admin/event/${ slug }/destroy`,
                    method: `DELETE`,
                    success: e => {
                        this.events.fresh()

                        this.alertSuccess(`Data has been destroy!`)
                    }
                })
            }
        }

        var event = new Event

        $(document).on(`click`, `button.destroy`, function() {
            let id = $(this).data(`id`)
            let title = $(this).data(`title`)

            app.alertConfirm({
                title: `Are you sure?`,
                text: `To delete the ${ title }.`,
                isConfirmed: e => {
                    event.destroy(id)
                }
            })
        })
    </script>
@endsection
