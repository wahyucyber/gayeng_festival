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
                            <div class="col-lg-12">
                                <h5>Acara</h5>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Kaegori</label>
                                    <select name="category_id" id="category_id" class="form-select category_id">
                                        <option value="">Pilih</option>
                                    </select>
                                    <small class="text-danger error" id="error-category_id"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="picture" class="form-label">Foto</label>
                                    <input type="file" name="picture" id="picture" class="form-control picture">
                                    <small class="text-danger error" id="error-picture"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="title" class="from-label">Judul</label>
                                    <input type="text" name="title" id="title" class="form-control title" autocomplete="off" placeholder="Judul" autofocus="autofocus">
                                    <small class="text-danger error" id="error-title"></small>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <label for="start_time" class="form-label">Tanggal Mulai</label>
                                    <input type="text" name="start_time" id="start_time" class="form-control start_time" autocomplete="off" placeholder="Tanggal">
                                    <small class="text-danger error" id="error-start_time"></small>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <label for="end_time" class="form-label">Tanggal Akhir</label>
                                    <input type="text" name="end_time" id="end_time" class="form-control end_time" autocomplete="off" placeholder="Tanggal">
                                    <small class="text-danger error" id="error-end_time"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4" class="form-control description" autocomplete="off" placeholder="Deskripsi"></textarea>
                                    <small class="text-danger error" id="error-description"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="location" class="form-label">Lokasi</label>
                                    <input type="text" name="location" id="location" class="form-control location" autocomplete="off" placeholder="Lokasi">
                                    <small class="text-danger error" id="error-location"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="term_and_condition" class="form-label">Syarat dan Ketentuan</label>
                                    <textarea name="term_and_condition" id="term_and_condition" rows="4" class="form-control term_and_condition" autocomplete="off" placeholder="Syarat dan Ketentuan"></textarea>
                                    <small class="text-danger error" id="error-term_and_condition"></small>
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

                this._initialize()

                this.description
                this.term_and_condition

                @if ($update == true)
                    this.show()
                @endif
            }

            get_categry() {
                this.api({
                    url: `/api/admin/event/category`,
                    success: e => {
                        let data = e.data

                        let option = `<option value="">Pilih</option>`

                        $.each(data, function (index, value) {
                            option += `<option value="${ value.id }">${ value.name }</option>`
                        })

                        $(`form#submit [name=category_id]`).html(option)
                    }
                })
            }

            _initialize() {
                this.get_categry()

                $(`form#submit input[name=start_time]`).flatpickr({
                    enableTime: true,
                    dateFormat: `Y-m-d h:i`,
                    time_24hr: true,
                    onChange: e => {
                        let start_time = $(`form#submit [name=start_time]`).val()
                        $(`form#submit [name=end_time]`).val(start_time)
                    }
                })

                $(`form#submit input[name=end_time]`).flatpickr({
                    enableTime: true,
                    dateFormat: `Y-m-d h:i`,
                    time_24hr: true
                })

                ClassicEditor.create( document.querySelector( '#description' ), {
                    ckfinder: {
                        uploadUrl: 'https://ckeditor.com/apps/ckfinder/3.5.0/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                    }
                } ).then(e => {
                    this.description = e
                }).catch( error => {
                    console.error( error )
                } )

                ClassicEditor.create( document.querySelector( '#term_and_condition' ), {
                    ckfinder: {
                        uploadUrl: 'https://ckeditor.com/apps/ckfinder/3.5.0/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                    }
                } ).then(e => {
                    this.term_and_condition = e
                }).catch( error => {
                    console.error( error )
                } )
            }

            @if ($update == true)
                show() {
                    this.api({
                        url: `/api/admin/event/{{ $slug }}/show`,
                        success: e => {
                            let data = e.data

                            $(`form#submit [name=title]`).val(data.title)
                            $(`form#submit [name=date]`).val(data.date)
                            $(`form#submit [name=start_time]`).val(data.start_time)
                            $(`form#submit [name=end_time]`).val(data.end_time)
                            $(`form#submit [name=price]`).val(data.price)
                            $(`form#submit [name=stock]`).val(data.stock)
                            this.description.setData(data.description)
                            this.term_and_condition.setData(data.term_and_condition)
                        }
                    })
                }
            @endif

            submit(e) {
                e.preventDefault()

                let formData = this.formData(`form#submit`)
                let url = `/api/admin/event/store`

                @if ($update == true)
                    formData.append(`_method`, `PUT`)
                    url = `/api/admin/event/{{ $slug }}/update`
                @endif

                $(`form#submit .error`).html(``)

                this.api({
                    url: url,
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
