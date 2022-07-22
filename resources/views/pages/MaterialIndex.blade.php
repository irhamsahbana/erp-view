@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Material'
        ],
    ];
@endphp

@section('content-header', 'Sparepard')

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
                        <x-table :thead="['Material', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <a
                                        href="{{ route('material.show', $data->id) }}"
                                        class="btn btn-warning"
                                        title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        <form
                                            style=" display:inline!important;"
                                            method="POST"
                                            action="{{ route('material.destroy', $data->id) }}">
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

                    <x-col class="d-flex justify-content-end">
                        {{ $datas->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'lg'">
        <form style="width: 100%" action="{{ route('material.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama Material'"
                    :col="12"
                    :name="'name'"
                    :required="true"></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection
