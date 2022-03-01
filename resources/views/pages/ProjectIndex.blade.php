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

    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Proyek'
        ],
    ];
@endphp

@section('content-header', 'Proyek')

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
                            :col="12"
                            :name="'cabang_id'"
                            :options="$dummyCabang"
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
                        <x-table :thead="['Cabang', 'Proyek', 'Aksi']">
                            @for($i = 0; $i < 10; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $dummyCabang[array_rand($dummyCabang)]['text'] }}</td>
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
                :col="6"
                :name="'cabang_id'"
                :options="$dummyCabang"
                :required="true"></x-in-select>
            <x-in-text
                :label="'Nama'"
                :placeholder="'Masukkaan Nama Proyek'"
                :col="6"
                :name="'proyek_name'"
                :required="true"></x-in-text>
        </x-row>
    </x-modal>
@endsection
