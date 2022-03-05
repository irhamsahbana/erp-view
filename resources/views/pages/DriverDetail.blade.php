@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Detail Pengendara'
        ],
    ];
@endphp

@section('content-header', 'Detail Pengendara')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('driver.store') }}" method="POST">
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
                            :label="'Nama'"
                            :placeholder="'Masukkan Nama'"
                            :col="12"
                            :name="'name'"
                            :value="$data->name"
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
