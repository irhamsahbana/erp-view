@extends('App')

@php

$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Nota Tagihan'
],
];

$is_open = [
[
"text" => "Nonaktif",
"value" => 0,
],
[
"text" => "Aktif",
"value" => 1,
],
];
@endphp

@section('content-header', 'Nota Tagihan')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible :title="'Tambah Journal'" :collapse="false">

            <form   action="{{ route('bill.store') }}" method="POST">
                @csrf
                @method('post')

                <x-in-select
                    :label="'Vendor'"
                    :placeholder="'Pilih Vedor'"
                    :col="12"
                    :name="'bill_vendor_id'"
                    :options="$options['vendors']"
                    :required="true">
                </x-in-select>
                {{-- <x-in-select
                    :label="'Item'"
                    :col="12"
                    :name="'bill_item_id'"
                    :options="$options['items']"
                    :required="true">
                </x-in-select> --}}

                <x-in-text
                    :type="'date'"
                    :label="'Tanggal Nota'"
                    :name="'recive_date'" :required="true">
                </x-in-text>
                <x-in-text
                    :type="'date'"
                    :label="'Batas Waktu'"
                    :name="'due_date'" :required="true">
                </x-in-text>

                <div class="col-sm-12">
                    <label for="">Catatan</label>
                    <textarea name="notes" class="form-control" cols="30" rows="5" required></textarea>
                </div>
                <x-col class="text-right mt-2">
                    <button type="submit" class="btn btn-primary float-right" data>Simpan</button>
                </x-col>
            </form>

        </x-card-collapsible>
    </x-row>
</x-content>
@endsection
@push('js')
@endpush
