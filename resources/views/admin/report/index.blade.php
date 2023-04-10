@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Laporan Penjualan"
])

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="text" name="date" id="date" class="form-control date" autocomplete="off" placeholder="Tanggal">
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="filter_status" class="form-select status">
                                    <option value="">Pilih</option>
                                    <option value="pending">pending</option>
                                    <option value="settlement">settlement</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3 d-flex justify-content-end">
                            <div class="d-flex flex-column m-auto position-relative">
                                <small><i class="ri-mastercard-line"></i> Pendapatan</small>
                                <h3><b id="total-pay">IDR 0</b></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Laporan Penjualan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed table-bordered table-hover table-sm" id="reports">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pembayaran</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Pengguna</th>
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
                                    <th>Pengguna</th>
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
        class Report extends App {
            constructor() {
                super()

                this.reports

                this._intialize()
                this.get()
                this.getTotalPay()
            }

            _intialize() {
                $(`input[name=date]`).flatpickr({
                    dateFormat: `Y-m-d`,
                    mode: `range`
                })
            }

            get() {
                this.reports = $(`table#reports`).laravelTable({
                    url: `${ app.baseUrl }/api/admin/report`,
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': app.authorization
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
                                return `<span class="badge bg-primary">${ e.invoice }</span>`
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

            filter() {
                this.reports.fresh({
                    date: $(`input[name=date]`).val(),
                    payment_status: $(`select[name=status]`).val()
                })
            }

            getTotalPay() {
                this.api({
                    url: `/api/admin/report/totalPay?date=${ $(`input[name=date]`).val() }&payment_status=${ $(`select[name=status]`).val() }`,
                    success: e => {
                        let data = e.data

                        $(`#total-pay`).html(this.rupiah(data.total_pay))
                    }
                })
            }
        }

        var report = new Report

        $(document).on(`change`, `input[name=date], select[name=status]`, function() {
            report.filter()
            report.getTotalPay()
        })
    </script>
@endsection
