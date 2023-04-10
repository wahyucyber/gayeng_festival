@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Transaksi"
])

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Catatan</h5>
                    <div>* Klik <b><u>Invoice</u></b> untuk melihat detail transaksi.</div>
                    <div>* Transaksi akan diverifikasi otomatis oleh sistem ketika customer telah membayar.</div>
                    <div>* Status <span class="badge bg-success">Settlement</span> adalah transaksi berhasil atau sukses.</div>
                    <div>* Status <span class="badge bg-warning">Pending</span> adalah transaksi masih menunggu pembayaran.</div>
                    <div>* Status <span class="badge bg-danger">Expire</span> adalah transaksi telah melewati batas waktu maksimal pembayaran.</div>
                    <div>* Status <span class="badge bg-danger">Cancel</span> adalah transaksi telah dibatalkan.</div>
                    <div>* Status <span class="badge bg-danger">Refund</span> adalah transaksi dikembalikan.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Data Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed table-bordered table-sm" id="orders">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pembayaran</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pembayaran</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
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
        class Order extends App {
            constructor() {
                super()

                this.orders

                this.get()
            }

            get() {
                this.orders = $(`table#orders`).laravelTable({
                    url: `${ this.baseUrl }/api/admin/order`,
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
                        placeholder: `Cari invoice...`,
                        customClass: `input-group-sm`
                    },
                    columns: [
                        {
                            data: `invoice`,
                            html: e => {
                                return `<span class="badge bg-primary show-order" data-invoice="${ e.invoice }">${ e.invoice }</span>`
                            }
                        },
                        {
                            data: `payment_type`,
                            sort: false,
                            html: e => {
                                return `<span class="text-uppercase">${ e.payment_type.replace(`_` , ` `) }</span>`;
                            }
                        },
                        {
                            data: `total_pay`,
                            html: e => {
                                return app.rupiah(e.total_pay)
                            }
                        },
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                let bg_status = `success`

                                if (e.payment_status == `pending`) {
                                    bg_status = `warning`
                                }else if (e.payment_status == `expire` || e.payment_status == `cancel` || e.payment_status == `deny`) {
                                    bg_status = `danger`
                                }else if (e.payment_status == `refund`) {
                                    bg_status = `dark`
                                }

                                return `<span class="badge bg-${ bg_status }">${ e.payment_status }</span>`
                            }
                        },
                        {
                            data: `created_at`,
                            html: e => {
                                return app.dateTimeFormat(e.created_at)
                            }
                        },
                        {
                            data: `user.name`,
                            sort: false
                        }
                    ]
                })
            }

            showOrder(invoice) {
                window.location=`{{ env("APP_URL") }}/admin/order/${ invoice}/show`
            }
        }

        var order = new Order

        $(document).on(`click`, `span.show-order`, function() {
            let invoice = $(this).data(`invoice`)

            order.showOrder(invoice)
        })
    </script>
@endsection