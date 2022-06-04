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

@section('content-header', 'Item-item Pembelian')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-col class="mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
            </x-col>
                    <x-col>
                        <x-table :thead="['Nama Item']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $data->name }}</td>


                                    {{-- <td>{{ $data->pay_date }}</td> --}}

                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $datas->links() }}
                    </x-col>
        </x-row>
    </x-content>
    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('bill-item.add') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>

                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkaan Nama Item'"
                    :col="12"
                    :name="'name'"
                    :required="true"></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>

@endsection

@push('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">

    <input hidden id="search-project" value="{{app('request')->input('project_id') ?? null }}">
   <input hidden id='url-project' value="{{route('project.index')}}">

   <input hidden id='search-vendor' value="{{app('request')->input('receivable_vendor_id') ?? null }}">
   <input hidden id='url-vendor' value="{{route('receivable-vendor.index')}}">
{{-- Modal --}}
 <meta name="url-order-change-status" content="{{ route('receivable-statuspaid.post', 'dummy-id') }}">

   <script>

    </script>
<style>
    th {
        text-align: center
    }
</style>
@endpush

