@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Tiket"
])

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3 d-flex justify-content-end">
            <a href="{{ route("staff.ticket.scan") }}" class="btn btn-success"><i class="ri-qr-scan-2-line"></i> Scan Tiket</a>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="event_id" class="form-label">Acara</label>
                                <select name="event_id" id="event_id" class="form-select event_id">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="filter_status" class="form-control status">
                                    <option value="">Pilih</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Settlement">Settlement</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Data Tiket</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed table-bordered table-sm" id="tickets">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Acara</th>
                                    <th style="width: 20%;">Code</th>
                                    <th style="width: 20%;">Status</th>
                                    <th style="width: 20%;">Nama</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <thead>
                                    <tr>
                                        <th>Acara</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
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
        class Ticket extends App {
            constructor() {
                super()

                this.tickets
            }

            _initialize() {
                this.apiSelect2({
                    element: `select#event_id`,
                    url: `/api/staff/event/select2`
                })
            }

            get() {
                this.tickets = $(`table#tickets`).laravelTable({
                    url: `${ app.baseUrl }/api/staff/ticket`,
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': app.authorization
                    },
                    search: {
                        placeholder: `Cari code...`,
                        customClass: `input-group-sm`
                    },
                    limit: {
                        customClass: `form-select-sm`
                    },
                    pagination: {
                        customClass: `pagination-sm`
                    },
                    columns: [
                        {
                            data: null,
                            sort: false,
                            html: e => {
                                let event = `-`

                                if (e.order != null && e.order.event_ticket != null && e.order.event_ticket.event != null) {
                                    event = e.order.event_ticket.event.title
                                }

                                return event
                            }
                        },
                        {
                            data: `code`
                        },
                        {
                            data: `status`,
                            sort: false,
                            html: e => {
                                let bg_badge = `success`

                                if (e.status == 'pending') {
                                    bg_badge = `warning`
                                }

                                return `<span class="badge bg-${ bg_badge }">${ e.status }</span>`
                            }
                        },
                        {
                            data: `name`
                        }
                    ]
                })
            }

            filter() {
                this.tickets.fresh({
                    event_id: $(`select#event_id`).val(),
                    status: $(`select#filter_status`).val()
                })
            }
        }

        var ticket = new Ticket

        ticket._initialize()
        ticket.get()

        $(document).on(`change`, `select#event_id, select#filter_status`, function() {
            ticket.filter()
        })
    </script>
@endsection
