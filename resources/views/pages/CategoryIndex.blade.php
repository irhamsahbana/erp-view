@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'List Kategori',
            'href' => route('category.list')
        ],
        [
            'name' => 'Jenis Jurnal'
        ],
    ];
@endphp

@section('content-header', 'Jenis Jurnal')

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
                        <x-table :thead="['Cabang', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <a
                                        {{-- block submit with js when click --}}
                                            href="{{ route('category.show', $data->id) }}"

                                            class="btn btn-warning"
                                            title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        <form style=" display:inline!important;" method="POST" action="{{ route('category.destroy', $data->id) }}">
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

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'lg'">
        <form style="width: 100%" action="{{ route('categoy.store') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="group_by" value="{{ app('request')->input('group_by') }}">
            <x-row>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama'"
                    :col="12"
                    :name="'label'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Catatan'"
                    :placeholder="'Masukkan Catatan'"
                    :col="12"
                    :name="'notes'"
                    :required="true"></x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal>

@endsection
