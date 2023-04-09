@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Scan Tiket"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end gap-3">
            <a href="{{ route("admin.ticket") }}" class="btn btn-secondary"><i class="ri-skip-back-mini-line"></i> Kembali</a>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Scan Tiket</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <video style="width: 100%" id="video"></video>
                        </div>
                        <div class="col-lg-6" id="response">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        class Ticket extends App {
            constructor() {
                super()

                this.active_camera()
            }

            active_camera() {
                // Mengambil elemen video
                var video = document.getElementById('video')

                // Membuat scanner QR code
                var scanner = new Instascan.Scanner({ video: video })

                // Menambahkan event listener untuk mendapatkan kode QR
                scanner.addListener('scan', function(content) {
                    app.api({
                        url: `/api/admin/ticket/confirm`,
                        method: `POST`,
                        data: {
                            'code': content
                        },
                        success: e => {
                            let data = e.data

                            $(`#response`).html(`
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Acara</label>
                                            <input type="text" class="form-control" value="${ data.order_item.event != null ? data.order_item.event.title : '-' }" />
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Kode</label>
                                            <input type="text" class="form-control" value="${ data.code }" />
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Status</label>
                                            <input type="text" class="form-control" value="${ data.status }" />
                                        </div>
                                        <div class="mb-3">
                                            <div class="alert alert-success">Data berhasil diperbarui!</div>
                                        </div>
                                    </div>
                                </div>
                            `)
                        },
                        error: err => {
                            app.alertDanger(`Ticket not found!`)
                        }
                    })
                    scanner.start(scanner.camera)
                })

                // Mengaktifkan kamera belakang atau kamera depan dan memulai scanning QR code
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        var backCamera = cameras.find(function(camera) { return camera.name.indexOf('back') !== -1; })
                        if (backCamera) {
                            scanner.start(backCamera)
                        } else {
                            scanner.start(cameras[0])
                        }
                    } else {
                        console.error('Tidak ada kamera yang tersedia')
                    }
                })
            }
        }

        var ticket = new Ticket
    </script>
@endsection
