@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td><b>Pembelian</b></td>
                        </tr>
                        <tr>
                            <td>
                                Ref #: {{ $purchase->ref_no }}<br />
                                Dicetak: {{ date('d/m/Y') }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                Cabang: {{ $purchase->branch->name }}<br />
                                Tanggal: {{ $purchase->created }}<br />
                                Vendor: {{ $purchase->vendor->name }}<br />
                                Pemesan: {{ $purchase->user }}<br />
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
                <td>No</td>
                <td id="detail-name">Nama Item</td>
                <td>Quantity</td>
                <td style="width:150px;">Harga</td>
                <td>Ket</td>
            </tr>

            @php
                $total = 0;
            @endphp
            @foreach ($purchaseDetail as $detail)
            @php
                $total += $detail->amount;
            @endphp
            <tr class="item">
                <td>{{ $loop->iteration }}</td>
                <td id="detail-name">{{ $detail->name }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ 'Rp. ' . number_format($detail->amount, 2) }}</td>
                <td>{{ $detail->notes }}</td>
            </tr>
            @endforeach

            <tr class="item">
                <td>Total</td>
                <td></td>
                <td></td>
                <td>{{ 'Rp.' . number_format($total, 2)  }}</td>
                <td></td>
            </tr>

        </table>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0" style="margin-b">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>Bagian Pembelian</td>
                            <td id="detail-name">Toko</td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>Pemesan</td>
                            <td>Penerima</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
@endsection