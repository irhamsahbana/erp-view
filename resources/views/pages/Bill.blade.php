@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Piutang Usaha'
        ],
    ];

    function abcd()
    {

    }
@endphp

@section('content-header', 'Bill')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'">
                <form style="width: 100%">
                    <x-row class="justify-content-md-center">
                        @if (Auth::user()->role == 'owner'  || Auth::user()->role == 'admin' )
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="4"
                            :name="'branch_id'"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['branches']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                            @endif
                        <x-in-select
                            :label="'Vendor'"
                            :placeholder="'Pilih Vendor'"
                            :col="4"
                            :name="'bill_vendor_id'"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['vendors']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('bill_vendor_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status Bayar'"
                            :placeholder="'Pilih Status Bayar'"
                            :col="4"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['status']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('is_paid') ?? null"
                            :required="false"></x-in-select>

                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Kirim dari'"
                            :col="6"
                            :value="app('request')->input('recive_date_start') ?? null"
                            :name="'recive_date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Kirim sampai:'"
                            :col="6"
                            :value="app('request')->input('recive_date_finish') ?? null"
                            :name="'recive_date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Bayar Dari:'"
                            :col="6"
                            :value="app('request')->input('pay_date_start') ?? null"
                            :name="'pay_date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Bayar sampai:'"
                            :col="6"
                            :value="app('request')->input('pay_date_finish') ?? null"
                            :name="'pay_date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Batas Waktu Dari:'"
                            :col="6"
                            :value="app('request')->input('due_date_start') ?? null"
                            :name="'due_date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Batas Waktu sampai:'"
                            :col="6"
                            :value="app('request')->input('due_date_finish') ?? null"
                            :name="'due_date_finish'"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('bill.index') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible :title="'Data Mutasi'">
                <x-row>
                    <x-col class="mb-3">
                        @if (Auth::user()->role == 'purchaser')

                            <a href={{route("bill.create")}} class="btn btn-primary">
                                Tambah
                            </a>

                        @endif
                    </x-col>

                    <x-col>
                        <x-table :thead="[ 'Ref','Cabang','Vendor', 'Tanggal Nota', 'Batas Waktu','Tanggal Bayar' , 'Keterangan', 'Jumlah' ,'Status Bayar','Action']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $data->ref_no }}</td>

                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->bill_vendor->name }}</td>
                                    <td>{{ date("d-m-Y", strtotime($data->recive_date)) }}</td>
                                    <td>{{ date("d-m-Y", strtotime($data->due_date)) }}</td>
                                    <td>
                                        @if($data->pay_date)
                                        {{ date("d-m-Y", strtotime($data->pay_date)) }}
                                        @else  -
                                    @endif
                                    </td>
                                    <td>{{ $data->notes }}</td>
                                    <td class="text-right">{{ number_format($data->amount) }}</td>
                                    <td class="text-center">
                                        @if ( $data->is_paid)
                                            <form action="{{ route('receivable-statuspaid.post', $data->id) }}" style="display:inline!important;" method="POST">
                                                @method('POST')
                                                @csrf
                                            <button
                                            type="submit"
                                            class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                                onclick="return confirm('Apakah anda ingin mengubah status pembayaran ini?')"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>
                                        </form>
                                         @else
                                            <button
                                            class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                            onclick="changeStatus({{$data->id}})"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>


                                        @endif
                                    </td>

                                    {{-- <td>
                                        {{abcd()}}
                                        @if($data->pay_date)
                                            {{ date("d-m-Y", strtotime($data->pay_date)) }}

                                        @endif
                                        </td> --}}
                                    {{-- <td>{{ $data->receivable_vendor->name }}</td>
                                    <td>{{ $data->notes }}</td>
                                    <td class="text-right">{{ number_format($data->amount) }}</td>
                                    <td class="text-center">
                                        @if ( $data->is_paid)
                                            <form action="{{ route('receivable-statuspaid.post', $data->id) }}" style="display:inline!important;" method="POST">
                                                @method('POST')
                                                @csrf
                                            <button
                                            type="submit"
                                            class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                                onclick="return confirm('Apakah anda ingin mengubah status pembayaran ini?')"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>
                                        </form>
                                         @else
                                            <button
                                            class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                            onclick="changeStatus({{$data->id}})"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>


                                        @endif
                                    </td>
                                    <td>
                                        <form
                                        style=" display:inline!important;"
                                        method="POST"
                                        action="{{ route('receivable.delete', $data->id) }}">
                                            @csrf
                                            @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                            title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>

                                    </td> --}}
                                    {{-- <td>{{ $data->pay_date }}</td> --}}

                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $datas->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    {{-- <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('bill.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>

                        <x-in-select
                            :label="'Vendor'"
                            :placeholder="'Pilih Vendor'"
                            :col="3"
                            :name="'new_bill_vendor_id'"
                            :options="$options['vendors']"
                            :value="app('request')->input('new_bill_vendor_id') ?? null"
                            :required="true"></x-in-select>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Terima'"
                            :name="'new_recive_date'"
                            :col="3"
                            :required="true"
                            ></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Batas Waktu'"
                            :name="'new_due_date'"
                            :col="3"
                            :required="true"
                            ></x-in-text>
                        <x-in-text
                            :type="'number'"
                            :label="'Jumlah'"
                            :name="'amount'"
                            :col="3"
                            :required="true"
                            ></x-in-text>
                        <x-in-text
                            :type="'text'"
                            :label="'Keterangan'"
                            :name="'notes'"
                            :col="12"
                            :required="true"
                            ></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal> --}}

    <x-modal :title="'Change Pay Status'" :id="'modal-change-status'" :size="'md'">
        <form style="width: 100%" action="" method="POST" id="form-status-change">
            @csrf
            @method('POST')

            <x-row>
                <input type="hidden" name="id" id="order_id" value="">
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :name="'pay_date'"
                    :required="true"
                    ></x-in-text>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin ingin mengubah status order data ini?')">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection

@push('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">

   <script>
        // function changeStatus(id) {
        //     $('#modal-change-status').modal('show');
        //     $('#form-status-change').trigger('reset');

        //     let url = $('meta[name="url-order-show"]').attr('content');
        //     url = url.replace('dummy-id', id);

        //     $.ajax({
        //         url: url,
        //         type: 'GET',
        //         dataType: 'json',
        //         cache: false,
        //         success: function(data) {
        //             $('#pay_date').val(data.pay_date);

        //             //change form action
        //             let url = $('meta[name="url-order-change-status"]').attr('content');
        //             url = url.replace('dummy-id', data.id);
        //             $('#form-status-change').attr('action', url);

        //         },
        //         error: function(data) {
        //             console.log(data);
        //         }
        //     });
        // }



    </script>
<style>
    th {
        text-align: center
    }
    label {
        text-align: center;
    }
</style>
@endpush

