@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Tambah Acara"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end gap-3">
            <a href="{{ route("admin.news") }}" class="btn btn-secondary"><i class="ri-skip-back-mini-line"></i> Kembali</a>
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
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label for="text" class="form-label">Teks</label>
                                    <textarea name="text" id="text" rows="4" class="form-control text" autocomplete="off" placeholder="Teks"></textarea>
                                    <small class="text-danger error" id="error-text"></small>
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
        class News extends App {
            constructor() {
                super()

                this.editor

                this._initialize()

                @if ($update == true)
                    this.show()
                @endif
            }

            _initialize() {
                ClassicEditor.create( document.querySelector( '#text' ), {
                    ckfinder: {
                        uploadUrl: 'https://ckeditor.com/apps/ckfinder/3.5.0/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                    }
                } ).then(e => {
                    this.editor = e
                }).catch( error => {
                    console.error( error )
                } )
            }

            @if ($update == true)
                show() {
                    this.api({
                        url: `/api/admin/news/{{ $slug }}/show`,
                        success: e => {
                            let data = e.data

                            $(`form#submit [name=title]`).val(data.title)
                            this.editor.setData(data.text)
                        }
                    })
                }
            @endif

            submit(e) {
                e.preventDefault()

                let formData = this.formData(`form#submit`)
                let url = `/api/admin/news/store`

                @if ($update == true)
                    formData.append(`_method`, `PUT`)
                    url = `/api/admin/news/{{ $slug }}/update`
                @endif

                $(`form#submit .error`).html(``)

                this.api({
                    url: url,
                    method: `POST`,
                    content_type: `multipart/form-data`,
                    data: formData,
                    success: e => {
                        window.location=`{{ route("admin.news") }}`
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

        var news = new News

        $(document).on(`submit`, `form#submit`, function(e) {
            news.submit(e)
        })
    </script>
@endsection
