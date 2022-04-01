@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><b>Mutasi Material</b></td>
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
                                Quary: {{ $data->project->name }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading green">
                <td>Detail</td>

                <td></td>
            </tr>

            <tr class="item">
                <td>Tanggal</td>
                <td>{{ date('d/m/Y',strtotime($data->created)) }}</td>
            </tr>

            <tr class="item">
                <td>Jenis Material</td>
                <td>{{ $data->material->name }}</td>
            </tr>
            <tr class="item">
                <td>Jenis Mutasi</td>
                <td>{{ $data->transaction_type == 1 ? 'Masuk' : 'Keluar'  }}</td>
            </tr>

            <tr class="item">
                <td>Volume</td>
                <td>{{ $data->volume }}</td>
            </tr>

            <tr class="item">
                <td>Harga Material</td>
                <td>{{ 'Rp. ' . number_format($data->material_price, 2) }}</td>
            </tr>

            <tr class="item">
                <td>Keterangan</td>
                <td>{{ $data->notes }}</td>
            </tr>
        </table>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0" style="margin-b">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Staff Material
                            </td>

                            <td style="text-align: left;">
                                Kasir
                            </td>

                            <td style="text-align: right">
                                Akuntansi
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
@endsection