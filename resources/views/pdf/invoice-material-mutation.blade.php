@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="1">
                    <table>
                        <tr>
                            <td><b>Mutasi Material</b></td>
                        </tr>
                        <tr>
                            <td>
                                Ref #<br />
                                Dicetak<br />
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1">
                    <table>
                        <tr>
                            <td><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td>
                                : {{ $data->ref_no }}<br />
                                : {{ date('d/m/Y') }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1"></td>
                <td colspan="1">
                    <table>
                        <tr>
                            <td><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td>
                                Cabang<br />
                                Quary<br />
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1">
                    <table>
                        <tr>
                            <td><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td>
                                : {{ $data->branch->name }}<br />
                                : {{ $data->project->name }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading green">
                <td>Detail</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr class="item">
                <td>Tanggal</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ date('d/m/Y',strtotime($data->created)) }}</td>
            </tr>

            <tr class="item">
                <td>Jenis Material</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $data->material->name }}</td>
            </tr>
            <tr class="item">
                <td>Jenis Mutasi</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $data->transaction_type == 1 ? 'Masuk' : 'Keluar'  }}</td>
            </tr>

            <tr class="item">
                <td>Volume</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $data->volume }}</td>
            </tr>

            <tr class="item">
                <td>Harga Material</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ 'Rp. ' . number_format($data->material_price, 2) }}</td>
            </tr>

            <tr class="item">
                <td>Keterangan</td>
                <td></td>
                <td></td>
                <td></td>
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