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
                <div class="card-header card-primary">
                    <h5 class="card-title">Tiket</h5>
                </div>
                <div class="card-body">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quas reprehenderit dicta commodi tempore ab odit ullam minus nam ad harum est at a voluptatum dolorem dolore rem, obcaecati error. Quaerat?
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

                this.show()
            }

            show() {
                this.api({
                    url: `/api/admin/event/{{ $slug }}/show`,
                    success: e => {
                        let data = e.data

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
                    }
                })
            }
        }

        var event = new Event
    </script>
@endsection
