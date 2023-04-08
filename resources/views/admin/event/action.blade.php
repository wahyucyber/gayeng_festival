@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Tambah Acara"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end gap-3">
            <a href="{{ route("admin.event") }}" class="btn btn-secondary"><i class="ri-skip-back-mini-line"></i> Kembali</a>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="submit">
                        <div class="row">
                            <div class="col-lg-5 mb-3">
                                <div class="form-group">
                                    <label for="picture" class="form-label">Foto</label>
                                    <input type="file" name="picture" id="picture" class="form-control picture">
                                    <small class="text-danger error" id="error-picture"></small>
                                </div>
                            </div>
                            <div class="col-lg-7 mb-3">
                                <div class="form-group">
                                    <label for="title" class="from-label">Judul</label>
                                    <input type="text" name="title" id="title" class="form-control title" autocomplete="off" placeholder="Judul" autofocus="autofocus">
                                    <small class="text-danger error" id="error-title"></small>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <label for="date" class="form-label">Tanggal</label>
                                    <input type="text" name="date" id="date" class="form-control date" autocomplete="off" placeholder="Tanggal">
                                    <small class="text-danger error" id="error-date"></small>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="form-group">
                                    <label for="start_time" class="form-label">Waktu Mulai</label>
                                    <input type="text" name="start_time" id="start_time" class="form-control start_time" autocomplete="off" placeholder="Waktu Mulai">
                                    <small class="text-danger error" id="error-start_time"></small>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="form-group">
                                    <label for="end_time" class="form-label">Waktu Selesai</label>
                                    <input type="text" name="end_time" id="end_time" class="form-control end_time" autocomplete="off" placeholder="Waktu Selesai">
                                    <small class="text-danger error" id="error-end_time"></small>
                                </div>
                            </div>
                            <div class="col-lg-9 mb-3">
                                <div class="form-group">
                                    <label for="price" class="form-label">Harga</label>
                                    <div class="input-group">
                                        <span class="input-group-text">IDR</span>
                                        <input type="number" name="price" id="price" class="form-control price" autocomplete="off" placeholder="Harga">
                                    </div>
                                    <small class="text-danger error" id="error-price"></small>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="form-group">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" name="stock" id="stock" class="form-control stock" autocomplete="off" placeholder="Stok">
                                    <small class="text-danger error" id="error-stock"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4" class="form-control description" autocomplete="off" placeholder="Deskripsi"></textarea>
                                    <small class="text-danger error" id="error-description"></small>
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
        class Event extends App {
            constructor() {
                super()

                this.editor

                this._initialize()
            }

            _initialize() {
                $(`form#submit input[name=date]`).flatpickr({
                    dateFormat: `Y-m-d`
                })

                $(`form#submit input[name=start_time], form#submit input[name=end_time]`).flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: `H:i`,
                    time_24hr: true
                })

                ClassicEditor.create( document.querySelector( '#description' ), {
                    ckfinder: {
                        uploadUrl: 'https://ckeditor.com/apps/ckfinder/3.5.0/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                    }
                } ).then(e => {
                    this.editor = e
                }).catch( error => {
                    console.error( error )
                } )
            }

            submit(e) {
                e.preventDefault()

                let formData = this.formData(`form#submit`)

                $(`form#submit .error`).html(``)

                this.api({
                    url: `/api/admin/event/store`,
                    method: `POST`,
                    content_type: `multipart/form-data`,
                    data: formData,
                    success: e => {
                        window.location=`{{ route("admin.event") }}`
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

        var event = new Event

        $(document).on(`submit`, `form#submit`, function(e) {
            event.submit(e)
        })
    </script>
@endsection
