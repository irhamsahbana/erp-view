@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'List Kategori'
        ],
    ];

    $datas = [
        ['group_by' => 'jurnal_categories', 'label' => 'Jenis Jurnal']
];
@endphp

@section('content-header', 'List Katgori')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col>
                        <x-table :thead="['Kategori']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('category.index', ['group_by' => $data['group_by']]) }}">
                                            <i class="fas fa-newspaper"></i> Jenis Jurnal
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
