@extends('App')

@php
    $dummyRole = ['owner', 'kacab', 'kasir', 'material'];

    $dummyCabang = [
        [
            'text' => 'Cabang A',
            'value' => 'A'
        ],
        [
            'text' => 'Cabang B',
            'value' => 'B'
        ],
        [
            'text' => 'Cabang C',
            'value' => 'C'
        ],
        [
            'text' => 'Cabang D',
            'value' => 'D'
        ],
    ];

    $dummyRoleO = [
        [
            'text' => 'Owner',
            'value' => 'owner'
        ],
        [
            'text' => 'Kepala Cabang',
            'value' => 'kacab'
        ],
        [
            'text' => 'Kasir',
            'value' => 'kasir'
        ],
        [
            'text' => 'Material',
            'value' => 'material'
        ],
    ];

    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Pengguna'
        ],
    ];
@endphp

@section('content-header', 'Pengguna')

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

                    <x-table :thead="['Cabang', 'Role', 'Username', 'Aksi']">
                        @for($i = 0; $i < 10; $i++)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $dummyCabang[array_rand($dummyCabang)]['text']  }}</td>
                                <td>{{ $dummyRoleO[array_rand($dummyRoleO)]['text'] }}</td>
                                <td>{{ \Str::random(4) }}</td>
                                <td>
                                    <a
                                        href="#"
                                        class="btn btn-warning"
                                        title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        title="Hapus"><i class="fas fa-trash-alt"></i> </button>
                                </td>
                            </tr>
                        @endfor
                    </x-table>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <x-row>
            <x-in-select
                :label="'Cabang'"
                :placeholder="'Pilih Cabang'"
                :col="6"
                :name="'cabangId'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Role'"
                :placeholder="'Pilih Role'"
                :col="6"
                :name="'role'"
                :options="$dummyRoleO"
                :required="true"></x-in-select>
            <x-in-text
                :label="'Username'"
                :placeholder="'Masukkan Username'"
                :col="4"
                :name="'username'"
                :required="true"></x-in-text>
            <x-in-text
                :label="'Password'"
                :placeholder="'Masukkan Password'"
                :col="4"
                :name="'password'"
                :required="true"></x-in-text>
            <x-in-text
                :label="'Konfirmasi Password'"
                :placeholder="'Masukkan Konfirmasi Password'"
                :col="4"
                :name="'password_confirm'"
                :required="true"></x-in-text>
        </x-row>
    </x-modal>
@endsection
