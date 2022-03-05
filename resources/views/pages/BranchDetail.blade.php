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
            'name' => 'Detail Cabang'
        ],
    ];
@endphp

@section('content-header', 'Detail Cabang')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('branch.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <x-in-text
                            :label="'Nama'"
                            :placeholder="'Masukkaan Nama Cabang'"
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
