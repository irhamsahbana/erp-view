@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><b>Hutang</b></td>
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
                                Proyek: {{ $data->project->name }}<br />
                                Vendor: {{ $data->vendor->name }}<br />
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
                <td>Jenis Mutasi Hutang</td>
                <td>{{ $data->type == '1' ? 'Hutang' : 'Piutang' }}</td>
            </tr>
            <tr class="item">
                <td>Jenis Transaksi</td>
                <td>{{ $data->type == '1' ? 'Penambahan' : 'Pengurangan' }}</td>
            </tr>

            <tr class="item">
                <td>Jumlah</td>
                <td>{{ 'Rp. ' . number_format($data->amount, 2) }}</td>
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
                            </td>

                            <td>
                                Kasir
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
@endsection