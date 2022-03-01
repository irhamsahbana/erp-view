@extends('App')

@php
    $dummyData = ['owner', 'kacab', 'kasir', 'material'];

    $dummyData2 = [
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

    $dummyData3 = [
        [
            'text' => 'Open',
            'value' => 'open'
        ],
        [
            'text' => 'Close',
            'value' => 'close'
        ],
    ];

    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Solar'
        ],
    ];
@endphp

@section('content-header', 'Solar')

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
                            :col="4"
                            :name="'cabang_id'"
                            :options="$dummyData2"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status'"
                            :placeholder="'Pilih Status'"
                            :col="4"
                            :name="'status'"
                            :options="$dummyData3"
                            :required="false"></x-in-select>
                        <x-in-text
                            :label="'Nomor Kendaraan'"
                            :placeholder="'Masukkaan Nomor Kendaraan'"
                            :col="4"
                            :name="'kendaraan_id'"
                            :required="false"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Mulai'"
                            :col="6"
                            :name="'tanggal_mulai'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Selesai'"
                            :col="6"
                            :name="'tanggal selesai'"></x-in-text>
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
                        <x-table :thead="['Tanggal', 'Cabang', 'Nomor Kendaraan', 'Jumlah (Liter)', 'Status', 'Aksi']">
                            @for($i = 0; $i < 10; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ date("Y-m-d", mt_rand(1262055681,1362055681)) }}</td>
                                    <td>{{ $dummyData2[array_rand($dummyData2)]['text'] }}</td>
                                    <td>{{ \Str::random(4) }}</td>
                                    <td class="text-right">{{ rand(50, 100) }}</td>
                                    <td>{{ $dummyData3[array_rand($dummyData3)]['text'] }}</td>
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
                :name="'cabang_id'"
                :options="$dummyData2"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Status'"
                :placeholder="'Pilih Status'"
                :col="6"
                :name="'status'"
                :options="$dummyData3"
                :required="true"></x-in-select>
            <x-in-select
                :label="'Nomor Kendaraan'"
                :placeholder="'Pilih Nomor Kendaraan'"
                :col="6"
                :name="'kendaraanId'"
                :options="$dummyData3"
                :required="true"></x-in-select>
            <x-in-text
                :type="'date'"
                :label="'Tanggal'"
                :col="6"
                :name="'tanggal'"
                :required="true"></x-in-text>
            <x-in-text
                :type="'number'"
                :label="'Jumlah (Liter)'"
                :col="6"
                :name="'jumlah'"
                :required="true"></x-in-text>
        </x-row>
    </x-modal>

@endsection
