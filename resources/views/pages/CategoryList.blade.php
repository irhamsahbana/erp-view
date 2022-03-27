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

    $categories = [
        ['group_by' => 'journal_categories', 'label' => 'Jenis Jurnal', 'icon' => 'fas fa-newspaper'],
        ['group_by' => 'debt_types', 'label' => 'Jenis Mutasi Hutang', 'icon' => 'fas fa-hand-holding-usd'],
    ];
@endphp

@section('content-header', 'List Kategori')

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
                            @foreach($categories as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('category.index', ['category' => $data['group_by']]) }}">
                                            <i class="{{ $data['icon'] }}"></i> {{ $data['label'] }}
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
