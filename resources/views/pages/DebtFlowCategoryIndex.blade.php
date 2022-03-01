@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Jenis Mutasi Hutang'
        ],
    ];
@endphp

@section('content-header', 'Jenis Mutasi Hutang')

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
                        <x-table :thead="['Jenis', 'Aksi']">
                            @for($i = 0; $i < 2; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Str::random(4) }}</td>
                                    <td>
                                        <a
                                            href="#"
                                            class="btn btn-warning"
                                            title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            @endfor
                        </x-table>
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'lg'">
        <x-row>
            <x-in-text
                :label="'Nama'"
                :placeholder="'Masukkan Jenis Mutasi Hutang'"
                :col="12"
                :name="'jenis'"
                :required="true"></x-in-text>
        </x-row>
    </x-modal>
@endsection
