@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="1">
                    <table>
                        <tr>
                            <td><b>Pembelian</b></td>
                        </tr>
                        <tr>
                            <td>
                                Ref#<br />
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
                                :{{ $purchase->ref_no }}<br />
                                :{{ date('d/m/Y') }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1"></td>
                <td colspan="1">
                    <table>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr>
                            <td>
                                Cabang <br />
                                Tanggal<br />
                                Vendor<br />
                                Pemesan <br/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1">
                    <table>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr>
                            <td>
                                : {{ $purchase->branch->name }}<br />
                                : {{ $purchase->created }}<br />
                                : {{ $purchase->vendor->name }}<br />
                                : {{ $purchase->user }}<br />
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