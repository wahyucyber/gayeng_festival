@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Scan Tiket"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end gap-3">
            <a href="{{ route("staff.ticket") }}" class="btn btn-secondary"><i class="ri-skip-back-mini-line"></i> Kembali</a>
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
            }

            active_camera() {
                // Mengambil elemen video
                var video = document.getElementById('video')

                // Membuat scanner QR code
                var scanner = new Instascan.Scanner({ video: video })

                // Menambahkan event listener untuk mendapatkan kode QR
                scanner.addListener('scan', function(content) {
                    app.api({
                        url: `/api/staff/ticket/confirm`,
                        method: `POST`,
                        data: {
                            // 'code': content
                            'code': '20230419YnZRc'
                        },
                        success: e => {
                            let data = e.data

                            let event = `-`

                            if (data.order != null && data.order.event_ticket != null && data.order.event_ticket.event != null) {
                                event = data.order.event_ticket.event.title
                            }

                            $(`#response`).html(`
                                <div class="row">
                                    <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                        <small class="text-muted">Acara</small>
                                        <h6 class="text-black text-bold">${ event }</h6>
                                    </div>
                                    <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                        <small class="text-muted">Kode</small>
                                        <h6 class="text-black text-bold">${ data.code }</h6>
                                    </div>
                                    <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                        <small class="text-muted">Nama</small>
                                        <h6 class="text-black text-bold">${ data.name }</h6>
                                    </div>
                                    <div class="col-lg-6 mb-3 d-flex gap-2 flex-column">
                                        <small class="text-muted">Identitas (${ data.identity != null ? data.identity.name : '-' })</small>
                                        <h6 class="text-black text-bold">${ data.identity_number }</h6>
                                    </div>
                                    <div class="col-lg-12 mb-3 d-flex gap-2 flex-column">
                                        <small class="text-muted">Email</small>
                                        <h6 class="text-black text-bold">${ data.email }</h6>
                                    </div>
                                    <div class="col-lg-12 mb-3 d-flex gap-2 flex-column">
                                        <small class="text-muted">Whatsapp</small>
                                        <h6 class="text-black text-bold">${ data.whatsapp }</h6>
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
                            scanner.start(backCamera,  { mirror: true })
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

        ticket.active_camera()
    </script>
@endsection
