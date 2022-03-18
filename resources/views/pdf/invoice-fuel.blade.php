@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><b>Pemakaian Solar</b></td>
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
                                {{-- 12345 Sunny Road<br />
                                Sunnyville, CA 12345 --}}
                            </td>

                            {{-- <td>
                                Acme Corp.<br />
                                John Doe<br />
                                john@example.com
                            </td> --}}
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- <tr class="heading">
                <td>Payment Method</td>

                <td>Check #</td>
            </tr>

            <tr class="details">
                <td>Check</td>

                <td>1000</td>
            </tr> --}}

            <tr class="heading">
                <td>Detail</td>

                <td></td>
            </tr>

            <tr class="item">
                <td>Tanggal</td>
                <td>{{ date('d/m/Y',strtotime($data->created)) }}</td>
            </tr>

            <tr class="item">
                <td>Nomor Kendaraan</td>
                <td>{{ $data->vehicle->license_plate }}</td>
            </tr>

            <tr class="item">
                <td>Jumlah (Liter)</td>
                <td>{{ $data->amount }}</td>
            </tr>

            <tr class="item">
                <td>Keterangan</td>
                <td>{{ $data->notes }}</td>
            </tr>

            {{-- <tr class="item last">
                <td>Domain name (1 year)</td>
                <td>$10.00</td>
            </tr> --}}

            {{-- <tr class="total">
                <td></td>

                <td>Total: $385.00</td>
            </tr> --}}
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