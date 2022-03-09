@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Order'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Detail Order')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('order.store') }}" method="POST">

                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <x-row>
                            <x-in-select
                                :label="'Cabang'"
                                :placeholder="'Pilih Cabang'"
                                :col="4"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Jumlah'"
                                :col="4"
                                :name="'amount'"
                                :value="$data->amount"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="4"
                                :name="'created'"
                                :value="$data->created"
                                :required="true"></x-in-text>
                            <x-in-text
                                :label="'Keterangan'"
                                :col="12"
                                :name="'notes'"
                                :value="$data->notes"
                                :required="false"></x-in-text>

                            <x-col class="text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </x-col>
                        </x-row>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
