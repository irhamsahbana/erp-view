@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Detail Kendaraan'
        ],
    ];
@endphp

@section('content-header', 'Detail Kendaraan')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('vehicle.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="12"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="$data->branch_id"
                            :required="true"></x-in-select>
                        <x-in-text
                            :label="'Nomor Plat Kendaraan'"
                            :placeholder="'Masukkan Nomor Plat Kendaraan'"
                            :col="12"
                            :name="'license_plate'"
                            :value="$data->license_plate"
                            :required="true"></x-in-text>
                        <x-col class="text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </x-col>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
