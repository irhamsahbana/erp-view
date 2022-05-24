@extends('pdf.invoice')

@section('body')
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="1">
                    <table>
                        <tr>
                            <td><b>Voucher</b></td>
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
                        <tr><td></td></tr>
                        <tr>
                            <td>
                                Cabang<br />
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1">
                    <table>
                        <tr><td></td></tr>
                        <tr>
                            <td>
                                : {{ $data->branch->name }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading yellow">
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
                <td>Jenis Voucher</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $data->type == '1' ? 'Pemasukan' : 'Pengeluaran' }}</td>
            </tr>

            <tr class="item">
                <td>Jumlah</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ 'Rp. ' . number_format($data->amount) }}</td>
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

    <div class="sign-box yellow">
        <table cellpadding="0" cellspacing="0" >
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
                            <td>
                                Mengetahui
                            </td>
                        </tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr>
                            <td>
                               (.................)
                            </td>
                            <td>
                               (.................)
                            </td>
                            <td>
                               (.................)
                            </td>
                            <td>
                               (.................)
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
@endsection
