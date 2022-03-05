@extends('App')

@php
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

    $dummyJenisMutasi = [
        ['text' => 'Hutang', 'value' => 'hutang'],
        ['text' => 'Piutang', 'value' => 'piutang'],
    ];

    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Vendor'
        ],
    ];
@endphp

@section('content-header', 'Mutasi Hutang')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'">
                <x-form>
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="6"
                            :name="'cabang_id'"
                            :options="$dummyCabang"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Jenis Mutasi'"
                            :placeholder="'Pilih Jenis Mutasi'"
                            :col="6"
                            :name="'cabang_id'"
                            :options="$dummyJenisMutasi"
                            :required="false"></x-in-select>
                        <x-col class="text-right">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </x-form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Cabang', 'Vendor', 'Jenis Mutasi', 'Nama', 'Aksi']">
                            @for($i = 0; $i < 10; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $dummyCabang[array_rand($dummyCabang)]['text'] }}</td>
                                    <td>{{ \Str::random(4) }}</td>
                                    <td>{{ $dummyJenisMutasi[array_rand($dummyJenisMutasi)]['text'] }}</td>
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

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <x-row>
            <x-in-select
                :label="'Cabang'"
                :placeholder="'Pilih Cabang'"
                :col="12"
                :name="'cabangId'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Vendor'"
                :placeholder="'Pilih Vendor'"
                :col="12"
                :name="'vendorId'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Jenis Mutasi'"
                :placeholder="'Pilih Jenis Mutasi'"
                :col="12"
                :name="'jenisMutasiId'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Tambah/Kurang'"
                :placeholder="'Pilih Tambah/Kurang'"
                :col="12"
                :name="'tambahKurang'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Status'"
                :placeholder="'Pilih Status'"
                :col="12"
                :name="'status'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-text
                :type="'date'"
                :label="'Tanggal'"
                :col="12"
                :name="'tanggal'"
                :required="true"></x-in-text>
            <x-in-text
                :type="'number'"
                :label="'Jumlah'"
                :col="12"
                :name="'jumlah'"
                :required="true"></x-in-text>
        </x-row>
    </x-modal>
@endsection
