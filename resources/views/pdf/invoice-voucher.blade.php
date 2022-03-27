@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><b>Voucher</b></td>
                        </tr>
                        <tr>
                            <td>
                                Ref #: {{ $data->ref_no }}<br />
                                Dicetak: {{ date('d/m/Y') }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Cabang: {{ $data->branch->name }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading yellow">
                <td>Detail</td>

                <td></td>
            </tr>

            <tr class="item">
                <td>Tanggal</td>
                <td>{{ date('d/m/Y',strtotime($data->created)) }}</td>
            </tr>

            <tr class="item">
                <td>Jenis Voucher</td>
                <td>{{ $data->type == '1' ? 'Pemasukan' : 'Pengeluaran' }}</td>
            </tr>

            <tr class="item">
                <td>Jumlah</td>
                <td>{{ 'Rp. ' . number_format($data->amount, 2) }}</td>
            </tr>

            <tr class="item">
                <td>Status Voucher</td>
                <td>{{ $data->status == '1' ? 'Urgent' : $data->status == '2' ? 'By Planning' : '' }}</td>
            </tr>

            <tr class="item">
                <td>Keterangan</td>
                <td>{{ $data->notes }}</td>
            </tr>
        </table>
    </div>

    <div class="sign-box yellow">
        <table cellpadding="0" cellspacing="0" style="margin-b">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Kasir
                            </td>

                            <td>
                                Pemberi/Penerima
                            </td>

                            <td>
                                Staff Akuntansi
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
@endsection