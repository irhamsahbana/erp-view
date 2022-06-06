@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Piutang Usaha'
        ],
    ];
@endphp

@section('content-header', 'Saldo Tagihan')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'">
                <form style="width: 100%">
                    <x-row lass="justify-content-md-center">
                        @if (Auth::user()->role == 'owner'  || Auth::user()->role == 'admin' )
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="4"
                            :name="'branch_id'"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['branches']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        @endif

                        <x-in-select
                            :label="'Vendor'"
                            :placeholder="'Pilih Vendor'"
                            :col="4"
                            :name="'bill_vendor_id'"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['vendors']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('bill_vendor_id') ?? null"
                            :required="false"></x-in-select>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('receivable.index') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>


                    <x-col>
                        <x-table :thead="['Cabang', 'Vendor', 'Saldo Hutang / Tagihan']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->branch->name }}</td>

                                    <td>{{ $data->bill_vendor->name }}</td>
                                    <td class="text-right">{{ number_format($data->total) }}</td>


                                    {{-- <td>{{ $data->pay_date }}</td> --}}

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

@push('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">


{{-- Modal --}}
 <meta name="url-order-change-status" content="{{ route('receivable-statuspaid.post', 'dummy-id') }}">

   <script>


        // modal

    </script>
<style>
    th {
        text-align: center
    }
</style>
@endpush

