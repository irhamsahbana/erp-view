@extends('App')

@php

$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Jurnal'
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

@section('content-header', 'Jurnal')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible :title="'Tambah Journal'" :collapse="false">

            <form action="{{ route('save.journal') }}" method="POST">
                @csrf
                @method('post')

                <x-in-select 
                    :label="'Cabang'" 
                    :placeholder="'Pilih Cabang'" 
                    :col="12" 
                    :name="'branch_id'"
                    :options="$options['branches']" 
                    :required="true">
                </x-in-select>

                <x-in-select 
                    :label="'Kategori'" 
                    :placeholder="'Pilih Kategori'" 
                    :col="12" 
                    :name="'journal_category_id'"
                    :options="$options['categories']" 
                    :required="true">
                </x-in-select>

                <x-in-text 
                    :type="'date'" 
                    :label="'Tanggal'" 
                    :name="'created'">
                </x-in-text>

                <div class="col-sm-12">
                    <label for="">Catatan</label>
                    <textarea name="notes" class="form-control" cols="30" rows="10" required></textarea>
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