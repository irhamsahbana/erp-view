@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Purchase Detail'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Nota Tagihan')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Detai Nota'" :collapse="false">
                <x-row>
                    <div class="row">
                        <x-in-text
                            :type="'text'"
                            :label="'Cabang'"
                            :col="4"
                            :readonly="'true'"
                            :value="$bill->branch->name">
                        </x-in-text>

                        <x-in-text
                            :type="'text'"
                            :label="'Nomor Ref.'"
                            :col="4"
                            :readonly="'true'"
                            :value="$bill->ref_no">
                        </x-in-text>

                        <x-in-text
                            :type="'text'"
                            :label="'Vendor'"
                            :col="4"
                            :readonly="'true'"
                            :value="$bill->bill_vendor->name">
                        </x-in-text>

                        <x-in-text
                            :type="'text'"
                            :label="'Tanggal Nota'"
                            :col="3"
                            :readonly="'true'"
                            :value="$bill->recive_date">
                        </x-in-text>
                        <x-in-text
                            :type="'text'"
                            :label="'Batas Waktu'"
                            :col="3"
                            :readonly="'true'"
                            :value="$bill->due_date">
                        </x-in-text>


                        <x-in-text
                            :type="'text'"
                            :label="'Status Bayar'"
                            :col="3"
                            :readonly="'true'"
                            :value="($bill->is_paid == 0) ? 'Belum Bayar' : 'Sudah Bayar'">
                        </x-in-text>
                        <x-in-text
                            :type="'text'"
                            :label="'Total'"
                            :col="3"
                            :readonly="'true'"
                            :value="$bill->amount">
                        </x-in-text>

                        <div class="col-sm-12">
                            <label for="">Catatan</label>
                            <textarea class="form-control" name="" id="" cols="30" rows="5"
                                readonly>{{ $bill->notes }}</textarea>
                        </div>
                    </div>
                </x-row>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>
                    <x-col>

                        <x-table :thead="[ 'Item','Satuan', 'Quantity', 'Harga Satuan', 'Total Harga', 'Aksi']">
                            @foreach($subBill as $sub)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sub->bill_item->name }}</td>
                                    <td>{{ $sub->unit }}</td>
                                    <td class="text-right">{{ $sub->quantity }}</td>
                                    <td class="text-right">{{ 'Rp. ' . number_format($sub->unit_price) }}</td>

                                    <td class="text-right">{{ 'Rp. ' . number_format($sub->total) }}</td>
                                    <td>
                                        <form
                                        style=" display:inline!important;"
                                        method="POST"
                                        action="{{ route('subbill.delete', $sub->id) }}">
                                            @csrf
                                            @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                            title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>

                                    </td>

                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>


                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('subbill.add') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="bill_id" value="{{ $bill->id }}">
            <input type="hidden" name="bill_vendor_id" value="{{ $bill->bill_vendor_id }}">
            <input type="hidden" name="branch_id" value="{{ $bill->branch_id }}">

            <x-row>
                {{-- {{$bill->bill_vendor_id}} --}}
                <x-in-select
                    :options="$options['items']"
                    :placeholder="'Pilih Item'"
                    :label="'Nama Barang'"
                    :col="3"
                    :name="'bill_item_id'"
                    :required="true"></x-in-select>
                <x-in-text

                    :label="'Satuan'"
                    :col="3"
                    :name="'unit'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :label="'Quantity'"
                    :col="3"
                    :name="'quantity'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :label="'Harga Satuan'"
                    :col="3"
                    :name="'unit_price'"
                    :required="true"></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
    <x-modal :title="'Ubah Harga'" :id="'edit-modal'" :size="'sm'">
        <form id="edit-price-form" style="width: 100%" method="POST">
            @csrf
            @method('PUT')
            <x-row>
                <x-in-text
                    :type="'number'"
                    :label="'Total Harga'"
                    :col="12"
                    :id="'edit-amount'"
                    :value="''"
                    :name="'amount'"
                    :required="true"></x-in-text>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
    <meta name='url-update-price' content="{{ route('purchase-detail.update-price', 'dummy-id') }}">

    {{-- Searching --}}
    <script>

        $(function(){
            $('.edit-price').on('click', function() {
                let id = this.id;
                let amount = this.name;

                let url = $('meta[name="url-update-price"]').attr('content');

                url = url.replace('dummy-id', id);

                $('#edit-price-form').attr('action', url);

                $('#edit-amount').val(amount);
            });
        });

    </script>
@endpush
