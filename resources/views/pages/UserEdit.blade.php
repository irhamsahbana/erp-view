@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Edit Pengguna'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Pengguna')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Edit Pengguna'" :collapse="false">
                <form style="width: 100%" action="{{ route('user.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <x-row>
                        <x-in-text
                            :type="'text'"
                            :label="'Username'"
                            :col="4"
                            :id="'in_username'"
                            :value="$data->username"
                            :name="'username'"
                            :readonly="true"
                            :required="true"></x-in-text>
                        <x-in-text
                            :type="'password'"
                            :label="'Password'"
                            :col="4"
                            :id="'in_password'"
                            :name="'password'"
                            :required="true"></x-in-text>
                        <x-in-text
                            :type="'password'"
                            :label="'Konfirmasi Password'"
                            :col="4"
                            :id="'password_confirmation'"
                            :name="'password_confirmation'"
                            :required="true"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('app') }}">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

        </x-row>
    </x-content>

@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

@endpush
