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
        <x-card-collapsible 
            :title="'Tambah Journal'" 
            :collapse="false">

            <form action="{{ route('update.journal', ['journal' => $journal->id]) }}" method="POST">
                @csrf
                @method('post')

                <x-in-select 
                    :label="'Cabang'" 
                    :placeholder="'Pilih Cabang'" 
                    :col="12" 
                    :name="'branch_id'"
                    :options="$options['branches']" 
                    :value="$journal->branch_id" 
                    :required="true">
                </x-in-select>

                <x-in-select 
                    :label="'Kategori'" 
                    :placeholder="'Pilih Kategori'" 
                    :col="12" 
                    :name="'journal_category_id'"
                    :options="$options['categories']" 
                    :value="$journal->journal_category_id" 
                    :required="true">
                </x-in-select>

                <x-in-select 
                    :label="'Status'" 
                    :placeholder="'Pilih Status'" 
                    :col="12" 
                    :name="'is_open'"
                    :options="$is_open" 
                    :value="$journal->is_open" 
                    :required="true">
                </x-in-select>

                <x-in-text 
                    :type="'date'" 
                    :label="'Tanggal'" 
                    :name="'created'" 
                    :value="$journal->created">
                </x-in-text>

                <div class="col-sm-12">
                    <label for="">Catatan</label>
                    <textarea name="notes" class="form-control" cols="30" rows="10" required>{{ old('notes') ? old('notes') : $journal->notes }}</textarea>
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