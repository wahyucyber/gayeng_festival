@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Detail Acara"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end gap-2">
            <a href="{{ route("admin.event") }}" class="btn btn-secondary d-flex align-items-center gap-1"><i class="ri-skip-back-mini-line"></i> Kembali</a>
            <a href="{{ route("admin.event.update", [
                "slug" => $slug
            ]) }}" class="btn btn-primary d-flex align-items-center gap-1"><i class="ri-edit-circle-line"></i> Edit</a>
        </div>
        <div class="col-lg-12" id="event-show">
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Tiket</h5>
                    <button type="button" class="btn btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#action-ticket" data-bs-title="Tambah Tiket" data-bs-action="create" data-bs-id=""><i class="ri-file-add-line"></i> Tambah</button>
                </div>
                <div class="card-body">
                    <form id="filter">
                        <div class="row mb-3">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <input type="text" name="search" id="search" class="form-control search" autocomplete="off" placeholder="Cari nama...">
                                </div>
                            </div>
                            <div class="col-lg-2 d-grid gap-0">
                                <button type="submit" class="btn btn-primary"><i class="ri-filter-2-line"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="row" id="show-tickets">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <x-dashboard.modal modal-id="action-ticket">
        <form id="submit">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label for="category" class="form-label">Kategori</label>
                        <input type="text" name="category" id="category" class="form-control category" autocomplete="off" placeholder="Kategori">
                        <small class="text-danger error" id="error-category"></small>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control name" autocomplete="off" placeholder="Nama">
                        <small class="text-danger error" id="error-name"></small>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="form-group">
                        <label for="event_ticket_type_id" class="form-label">Jenis Tiket</label>
                        <select name="event_ticket_type_id" id="event_ticket_type_id" class="form-control event_ticket_type_id">
                            <option value="">Pilih</option>
                        </select>
                        <small class="text-danger error" id="error-event_ticket_type_id"></small>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="form-group">
                        <label for="Stock" class="form-label">Stok</label>
                        <input type="number" name="stock" id="stock" class="form-control stock" autocomplete="off" placeholder="Stok">
                        <small class="text-danger error" id="error-stock"></small>
                    </div>
                </div>
                <div class="col-lg-5 mb-3">
                    <div class="form-group">
                        <label for="amount_per_transaction" class="form-label">Jumlah (per Transaksi)</label>
                        <input type="number" name="amount_per_transaction" id="amount_per_transaction" class="form-control amount_per_transaction" autocomplete="off" placeholder="Jumlah Tiket (per Transaksi)">
                        <small class="text-danger error" id="error-amount_per_transaction"></small>
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="form-group">
                        <label for="price" class="form-label">Harga <sup class="text-primary">* Isikan 0 jika harga <u>gratis</u>.</sup></label>
                        <div class="input-group">
                            <span class="input-group-text">IDR</span>
                            <input type="number" name="price" id="price" class="form-control price" autocomplete="off" placeholder="Harga">
                        </div>
                        <small class="text-danger error" id="error-price"></small>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Mulai Penjualan</label>
                        <input type="text" name="start_date" id="start_date" class="form-control start_date" autocomplete="off" placeholder="Mulai Penjualan">
                        <small class="text-danger error" id="error-start_date"></small>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label for="end_date" class="form-label">Akhir Penjualan</label>
                        <input type="text" name="end_date" id="end_date" class="form-control end_date" autocomplete="off" placeholder="Akhir Penjualan">
                        <small class="text-danger error" id="error-end_date"></small>
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="row">
                        <div class="col-lg-12 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="on_sale" checked>
                                <label class="form-check-label" for="on_sale">On Sale</label>
                            </div>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-end align-items-center gap-2">
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1"><i class="ri-save-3-line"></i> Simpan</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary d-flex align-items-center gap-1"><i class="ri-close-circle-line"></i> Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-dashboard.modal>
@endsection

@section('javascript')
    <script type="text/javascript">
        class Event extends App {
            constructor() {
                super()

                this.event_id
            }

            _initialize() {
                $(`form#submit input[name=start_date], form#submit input[name=end_date]`).flatpickr({
                    enableTime: true,
                    dateFormat: `Y-m-d h:i`,
                    time_24hr: true,
                    // onChange: function(selectedDates, dateStr, instance){
                    //     if (dateStr)
                    //         instance.close()
                    // }
                })
            }

            show() {
                this.api({
                    url: `/api/admin/event/{{ $slug }}/show`,
                    success: e => {
                        let data = e.data

                        event.event_id = data.id

                        let output = `
                            <div class="card">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <img src="${ data.picture }" style="width: 100%;" alt="${ data.title }"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card-header">
                                            <h5 class="card-title">Detail Acara</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <h5 class="mb-2 d-flex align-items-center gap-1 text-dark"><i class="ri-film-line"></i> Nama</h5>
                                                    <p>${ data.title }</p>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <h5 class="mb-2 d-flex align-items-center gap-1 text-dark"><i class="ri-git-merge-line"></i> Kategori</h5>
                                                    <p>${ data.category != null ? data.category.name : `-` }</p>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <h5 class="m-b-2d-flex align-items-center gap-1 text-dark"><i class="ri-calendar-event-fill"></i> Tanggal</h5>
                                                    <p>${ this.dateTimeFormat(data.start_time) } - ${ this.dateTimeFormat(data.end_time) }</p>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <h5 class="m-b-2d-flex align-items-center gap-1 text-dark"><i class="ri-map-pin-line"></i> Lokasi</h5>
                                                    <p>${ data.location }</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `

                        $(`#event-show`).html(output)

                        event.ticket_get(`/api/admin/event/ticket?page=1`, data.id)
                    }
                })
            }

            ticket_type_get() {
                this.api({
                    url: `/api/admin/event/ticket/type`,
                    success: e => {
                        let data = e.data

                        let option = `<option value="">Pilih</option>`

                        $.each(data, function (index, value) {
                            option += `<option value="${ value.id }">${ value.name }</option>`
                        })

                        $(`#action-ticket form#submit [name=event_ticket_type_id]`).html(option)
                    }
                })
            }

            ticket_get(url = `/api/admin/event/ticket?page=1`, event_id) {
                this.api({
                    url: `${ url }&search=${ $(`form#filter [name=search]`).val() }&event_id=${ event_id }`,
                    success: e => {
                        let data = e.data
                        let next_page_url = e.next_page_url
                        let current_page = e.current_page

                        let output = ``

                        $.each(data, function (index, value) {
                            let on_sale = `<span class="badge bg-danger">Tidak</span>`

                            if (value.on_sale == 1) {
                                on_sale = `<span class="badge bg-success">Ya</span>`
                            }

                            output += `
                                <div class="col-lg-4">
                                    <div class="card p-3 shadow-lg" style="border-radius: 20px;">
                                        <div class="card-header border-bottom-dashed d-flex justify-content-between">
                                            <h6 class="d-flex align-items-center gap-1"><i class="ri-ticket-2-line"></i> ${ value.category }</h6>
                                            <span>${ value.event_ticket_type != null ? value.event_ticket_type.name : `-` }</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">Nama</small>
                                                    <h6 class="text-black">${ value.name }</h6>
                                                </div>
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">Harga Tiket</small>
                                                    <h6 class="text-black">${ app.rupiah(value.price) }</h6>
                                                </div>
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">Maks. Pembelian</small>
                                                    <h6 class="text-black">${ value.amount_per_transaction }</h6>
                                                </div>
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">Stok Tiket</small>
                                                    <h6 class="text-black">${ value.stock }</h6>
                                                </div>
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">Mulai Penjualan</small>
                                                    <h6 class="text-black">${ value.start_date }</h6>
                                                </div>
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">Akhir Penjualan</small>
                                                    <h6 class="text-black">${ value.end_date }</h6>
                                                </div>
                                                <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                                    <small class="text-muted">On Sale</small>
                                                    <h6 class="text-black">${ on_sale }</h6>
                                                </div>
                                                <div class="col-lg-6 d-flex align-items-center gap-1">
                                                    <button type="button" class="btn btn-danger btn-icon btn-sm" id="destroy" data-bs-name="${ value.name }" data-bs-id="${ value.id }"><i class="ri-delete-bin-6-line"></i></button>
                                                    <button type="button" class="btn btn-primary btn-icon btn-sm" id="update" data-bs-toggle="modal" data-bs-target="#action-ticket" data-bs-title="Edit Tiket" data-bs-action="update" data-bs-id="${ value.id }"><i class="ri-edit-circle-line"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `
                        })

                        $(`#show-tickets #show-more`).remove()

                        if (next_page_url != null) {
                            output += `
                                <div class="col-lg-12 mt-3 d-flex justify-content-center" id="show-more">
                                    <button type="button" class="btn btn-primary" data-next_page_url="${ next_page_url }" data-event_id="${ event_id }">Lihat Selengkapnya</button>
                                </div>
                            `
                        }

                        if (data.length == 0) {
                            output = `
                                <div class="py-4 text-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                            trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                                            style="width:72px;height:72px">
                                        </lord-icon>
                                    </div>

                                    <div class="mt-4">
                                        <h5>Maaf! Tidak Ada Hasil Ditemukan</h5>
                                    </div>
                                </div>
                            `
                        }

                        if (current_page == 1) {
                            $(`#show-tickets`).html(output)
                        }else {
                            $(`#show-tickets`).append(output)
                        }
                    }
                })
            }

            ticket_store(e) {
                e.preventDefault()

                let formData = this.formData(`#action-ticket form#submit`)

                let action = $(`#action-ticket [name=action]`).val()
                let id = $(`#action-ticket [name=id]`).val()
                let url = `/api/admin/event/ticket/store`

                let on_sale = 0

                if ($(`#action-ticket form#submit #on_sale`)[0].checked) {
                    on_sale = 1
                }

                formData.append(`event_id`, this.event_id)
                formData.append(`on_sale`, Boolean(on_sale))

                if (action == `update`) {
                    formData.append(`_method`, `PUT`)
                    url = `/api/admin/event/ticket/${ id }/update`
                }

                $(`#action-ticket form#submit .error`).html(``)

                this.api({
                    url: url,
                    method: `POST`,
                    data: formData,
                    success: e => {
                        $(`#action-ticket form#submit input`).val(``)
                        $(`#action-ticket form#submit select`).val(``).trigger(`change`)

                        this.alertSuccess(`Data berhasil disimpan!`)

                        $(`#action-ticket form#submit [data-bs-dismiss=modal]`).trigger(`click`)

                        this.show()
                    },
                    error: err => {
                        let error = err.message

                        $.each(error, function (index, value) {
                            $(`#action-ticket form#submit .error#error-${ index }`).html(`* ${ value }`)
                        })
                    }
                })
            }

            ticket_show(id) {
                this.api({
                    url: `/api/admin/event/ticket/${ id }/show`,
                    success: e => {
                        let data = e.data

                        $(`#action-ticket form#submit [name=category]`).val(data.category)
                        $(`#action-ticket form#submit [name=name]`).val(data.name)
                        $(`#action-ticket form#submit [name=event_ticket_type_id]`).val(data.event_ticket_type_id).trigger(`change`)
                        $(`#action-ticket form#submit [name=stock]`).val(data.stock)
                        $(`#action-ticket form#submit [name=amount_per_transaction]`).val(data.amount_per_transaction)
                        $(`#action-ticket form#submit [name=price]`).val(data.price)
                        $(`#action-ticket form#submit [name=start_date]`).val(data.start_date)
                        $(`#action-ticket form#submit [name=end_date]`).val(data.end_date)

                        $(`#action-ticket form#submit #on_sale`).prop(`checked`, false)

                        if (data.on_sale == 1) {
                            $(`#action-ticket form#submit #on_sale`).prop(`checked`, true)
                        }
                    }
                })
            }

            ticket_destroy(id) {
                this.api({
                    url: `/api/admin/event/ticket/${ id }/destroy`,
                    method: `DELETE`,
                    success: e => {
                        this.ticket_get()

                        this.alertSuccess(`Data has been destroy!`)
                    }
                })
            }
        }

        var event = new Event

        event._initialize()
        event.show()
        event.ticket_type_get()

        $(document).on(`submit`, `#action-ticket form#submit`, function(e) {
            event.ticket_store(e)
        })

        $(document).on(`click`, `#action-ticket [data-bs-dismiss=modal]`, function() {
            $(`#action-ticket form#submit .error`).html(``)
            $(`#action-ticket form#submit input`).val(``)
            $(`#action-ticket form#submit select`).val(``).trigger(``)
            $(`#action-ticket form#submit #on_sale`).prop(`checked`, true)
        })

        $(document).on(`click`, `#show-tickets #show-more button`, function() {
            let next_page_url = $(this).data(`next_page_url`)
            let event_id = $(this).data(`event_id`)

            event.ticket_get(next_page_url, event_id)
        })

        $(document).on(`click`, `#show-tickets #update`, function() {
            let id = $(this).data(`bs-id`)

            event.ticket_show(id)
        })

        $(document).on(`click`, `#show-tickets #destroy`, function() {
            let id = $(this).data(`bs-id`)
            let name = $(this).data(`bs-name`)

            app.alertConfirm({
                title: `Are you sure?`,
                text: `To delete the ${ name }.`,
                isConfirmed: e => {
                    event.ticket_destroy(id)
                }
            })
        })

        $(document).on(`submit`, `form#filter`, function(e) {
            e.preventDefault()

            event.ticket_get()
        })
    </script>
@endsection
