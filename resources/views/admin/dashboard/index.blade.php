@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Dashboard"
])

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row m-auto">
                        <div class="col-lg-8 d-flex flex-column">
                            <div class="fs-6">Acara Aktif</div>
                            <div class="fs-3" id="event-active">0</div>
                        </div>
                        <div class="col-lg-4 d-grid align-items-center">
                            <i class="ri-bank-card-2-line display-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row m-auto">
                        <div class="col-lg-8 d-flex flex-column">
                            <div class="fs-6">Total Tiket Terjual</div>
                            <div class="fs-3" id="tickets-sold">0</div>
                        </div>
                        <div class="col-lg-4 d-grid align-items-center">
                            <i class="ri-ticket-2-line display-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row m-auto">
                        <div class="col-lg-8 d-flex flex-column">
                            <div class="fs-6">Total Penjualan</div>
                            <div class="fs-3" id="orders">0</div>
                        </div>
                        <div class="col-lg-4 d-grid align-items-center">
                            <i class="ri-mastercard-line display-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        class Dashboard extends App {
            constructor() {
                super()
            }

            get() {
                this.api({
                    url: `/api/admin/dashboard`,
                    success: e => {
                        let data = e.data

                        $(`#event-active`).html(this.numberFormat(data.events))
                        $(`#tickets-sold`).html(this.numberFormat(data.tickets_sold))
                        $(`#orders`).html(this.rupiah(data.orders))
                    }
                })
            }
        }

        var dashboard = new Dashboard

        dashboard.get()
    </script>
@endsection
