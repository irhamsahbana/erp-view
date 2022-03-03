@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Detail Pengguna'
        ],
    ];
@endphp

@section('content-header', 'Detail Pengguna')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('user.store') }}" method="POST">
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
                        <x-in-select
                            :label="'Role'"
                            :placeholder="'Pilih Role'"
                            :col="12"
                            :name="'role'"
                            :options="$options['roles']"
                            :value="$data->role"
                            :required="true"></x-in-select>
                        <x-in-text
                            :label="'Username'"
                            :placeholder="'Masukkan Username'"
                            :col="12"
                            :name="'username'"
                            :value="$data->username"
                            :required="true"></x-in-text>
                        {{-- <x-in-text
                            :label="'Password'"
                            :placeholder="'Masukkan Password'"
                            :col="12"
                            :name="'password'"></x-in-text>
                        <x-in-text
                            :label="'Konfirmasi Password'"
                            :placeholder="'Masukkan Konfirmasi Password'"
                            :col="12"
                            :name="'password_confirmation'"></x-in-text> --}}

                        <x-col class="text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </x-col>
                    </form>

                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
