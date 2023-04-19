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
                                <small>({{ $response["data"]["identity"] != null ? $response["data"]["identity"]["name"] :
                                    "-" }}) {{ $response["data"]["identity_number"] }}</small><br>
                                <small>{{ $response["data"]["name"] }}</small><br>
                                <small>{{ $response["data"]["email"] }}</small><br>
                                <small>{{ $response["data"]["whatsapp"] }}</small>
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
                                            <th width="20%">Code</th>
                                            <th width="25%">Identitas</th>
                                            <th width="20%">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($response["data"]["tickets"] as $item)
                                            <tr>
                                                <td>{{ $item["code"] }}</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-6 text-muted" style="font-size: 11px;">Nama</div>
                                                        <div style="font-size: 11px;" class="col-lg-6 d-flex justify-content-end text-black">{{ $item["name"] }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 text-muted" style="font-size: 11px;">Identitas ({{ $item["identity"] != null ? $item["identity"]["name"] : "-" }})</div>
                                                        <div style="font-size: 11px;" class="col-lg-6 d-flex justify-content-end text-black">{{ $item["identity_number"] }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 text-muted" style="font-size: 11px;">Nama</div>
                                                        <div style="font-size: 11px;" class="col-lg-6 d-flex justify-content-end text-black">{{ $item["name"] }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 text-muted" style="font-size: 11px;">Email</div>
                                                        <div style="font-size: 11px;" class="col-lg-6 d-flex justify-content-end text-black">{{ $item["email"] }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 text-muted" style="font-size: 11px;">Whatsapp</div>
                                                        <div style="font-size: 11px;" class="col-lg-6 d-flex justify-content-end text-black">{{ $item["whatsapp"] }}</div>
                                                    </div>
                                                </td>
                                                <td style="text-align: right;">
                                                    <div class="d-flex justify-content-between">
                                                        <div>IDR</div>
                                                        <div>{{ number_format($response["data"]["price"]) }}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">Subtotal</td>
                                            <td style="text-align: right;">
                                                <div class="d-flex justify-content-between">
                                                    <div>IDR</div>
                                                    <div>{{ number_format($response["data"]["pay"]) }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">Biaya Admin ({{ $response["data"]["payment_type"] }})</td>
                                            <td style="text-align: right;">
                                                <div class="d-flex justify-content-between">
                                                    <div>IDR</div>
                                                    <div>{{ number_format($response["data"]["admin_fee"]) }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total Bayar</th>
                                            <th style="text-align: right;">
                                                <div class="d-flex justify-content-between">
                                                    <div>IDR</div>
                                                    <div>{{ number_format($response["data"]["total_pay"]) }}</div>
                                                </div>
                                            </th>
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
