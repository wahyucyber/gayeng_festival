@extends('_layout.app', [
    "dashboard" => true,
    "title" => "Detail Transaksi " . $invoice
])

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-end mb-3">
            <a href="{{ route("admin.order") }}" class="btn btn-secondary"><i class="ri-skip-back-mini-line"></i> Kembali</a>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header card-primary">
                    <h5 class="card-title">Invoice {{ $invoice }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">KEPADA</label><br>
                                <small>{{ $response["data"]["user"]["name"] }}</small><br>
                                <small>{{ $response["data"]["user"]["email"] }}</small><br>
                                <small>{{ $response["data"]["user"]["address"] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">TANGGAL</label><br>
                                <small>{{ $response["data"]["created_at"] }}</small>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">INVOICE</label><br>
                                <small>{{ $response["data"]["invoice"] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th width="20%">Nama</th>
                                            <th width="20%">Harga</th>
                                            <th width="20%">QTY</th>
                                            <th width="20%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($response["data"]["order_items"] as $item)
                                            <tr>
                                                <td>{{ $item["event"] != null ? $item["event"]["title"] : "-" }}</td>
                                                <td>IDR {{ number_format($item["price"]) }}</td>
                                                <td>{{ number_format($item["qty"]) }}</td>
                                                <td style="text-align: right; font-weight: bold;">IDR {{ number_format($item["total"]) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Biaya Admin <sup style="font-weight: normal;">{{ $response["data"]["payment_type"] }}</sup></th>
                                            <th style="text-align: right;">IDR {{ number_format($response["data"]["admin_fee"]) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Subtotal</th>
                                            <th style="text-align: right;">IDR {{ number_format($response["data"]["pay"]) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Total Bayar</th>
                                            <th style="text-align: right;">IDR {{ number_format($response["data"]["total_pay"]) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
