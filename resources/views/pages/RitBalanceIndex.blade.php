@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Saldo Hutang Ritase'
        ],
    ];
@endphp

@section('content-header', 'Saldo Hutang Ritase')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col>
                        <x-table :thead="['Cabang', 'proyek', 'Pengendara', 'Ref Mutasi Material', 'Total']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->driver->name }}</td>
                                    <td>{{ $data->materialMutation->ref_no }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $datas->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
