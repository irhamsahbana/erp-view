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

@section('content-header', 'Purchase Detail')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Ref', 'Cabang', 'Nama Barang', 'Total Harga', 'Volume', 'Satuan', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->purchase->ref_no }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->amount, 2) }}</td>
                                    <td>{{ $data->qty }}</td>
                                    <td>{{ $data->unit }}</td>
                                    <td>
                                        {{-- @if ($data->is_open) --}}
                                            <button
                                                href=""
                                                name="{{ $data->amount }}"
                                                class="btn btn-warning edit-price"
                                                id="{{ $data->id }}"
                                                title="Ubah Harga" data-toggle="modal" data-target="#edit-modal"><i class="fas fa-pencil-alt"></i></button>
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('purchase-detail.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        {{-- @endif --}}
                                        @if(Auth::user()->role == 'owner')
                                            {{-- <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('purchase.change-status', $data->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                <button
                                                    type="submit"
                                                    class="btn btn-secondary"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah staus data ini?')"
                                                    title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                            </form> --}}
                                        @endif
                                    </td>
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

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('purchase-detail.store') }}" method="POST">
            @csrf
            @method('POST')

            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
            <input type="hidden" name="branch_id" value="{{ $purchase->branch_id }}">

            <x-row>
                <x-in-text
                    :type="'text'"
                    :label="'Nama Barang'"
                    :col="4"
                    :id="'in_name'"
                    :name="'name'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :label="'Total Harga'"
                    :col="4"
                    :id="'in_amount'"
                    :name="'amount'"
                    :required="false"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :label="'Volume'"
                    :col="4"
                    :id="'in_qty'"
                    :name="'qty'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'text'"
                    :label="'Satuan'"
                    :col="4"
                    :id="'in_unit'"
                    :name="'unit'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'text'"
                    :label="'Notes'"
                    :col="4"
                    :id="'in_notes'"
                    :name="'notes'"
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
        // $('.editHarga').click(function(){
        //     let id = this.id
        //     console.log(id)

        //     let bebas = "{{ route('purchase-detail.update-price'," + id + ")}}"
        //     console.log(bebas)
        //     $('#formEditHarga').attr('action', bebas)
        // })

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
