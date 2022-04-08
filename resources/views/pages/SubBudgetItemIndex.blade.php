@extends('App')

@php
$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Sub Mata Anggaran'
],
];
@endphp

@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Sub Mata Anggaran')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible>
            <x-row>
                <x-col class="mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#add-modal">Tambah</button>
                </x-col>

                <x-col>
                    <x-table
                        :thead="['Jenis Laporan', 'Kelompok Mata Anggaran', 'Mata Anggaran', 'Nama', 'Saldo normal', 'Aksi']">
                        @foreach($datas as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->reportCategory->label }}</td>
                            <td>{{ $data->budgetItemGroup->name }}</td>
                            <td>{{ $data->budgetItem->name }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->normalBalance->label }}</td>
                            <td>
                                <a type="button" class="btn btn-warning" title="Edit" onclick="edit({{ $data->id }})"
                                    href="javascript:void(0)"><i class="fas fa-pencil-alt"></i></a>
                                <form style=" display:inline!important;" method="POST"
                                    action="{{ route('sbi.destroy', $data->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
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

<x-modal :title="'Tambah Data'" :id="'add-modal'">
    <form style="width: 100%" action="{{ route('sbi.store') }}" method="POST">
        @csrf
        @method('POST')

        <x-row>

            <x-in-select
                :label="'Jenis Laporan'"
                :placeholder="'Pilih Jenis Laporan'"
                :col="12"
                :id="'report_category_id_in'"
                :name="'report_category_id'"
                :options="$options['reportCategories']"
                :required="true">
            </x-in-select>

            <x-in-select
                :label="'Kelompok Mata Anggaran'"
                :placeholder="'Pilih Kelompok Mata Anggaran'"
                :col="12"
                :id="'budget_item_group_id_in'"
                :name="'budget_item_group_id'"
                :required="true">
            </x-in-select
            >
            <x-in-select
                :label="'Mata Anggaran'"
                :placeholder="'Pilih Mata Anggaran'"
                :col="12"
                :id="'budget_item_id_in'"
                :name="'budget_item_id'"
                :required="true">
            </x-in-select>

            <x-in-select
                :label="'Saldo Normal'"
                :placeholder="'Pilih Saldo Normal'"
                :col="12"
                :id="'normal_balance_id_in'"
                :name="'normal_balance_id'"
                :required="true">
            </x-in-select>

            <x-in-text
                :label="'Nama'"
                :placeholder="'Masukkaan Nama'"
                :col="12"
                :id="'name_in'"
                :name="'name'"
                :required="true">
            </x-in-text>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </x-row>
    </form>
</x-modal>

<x-modal :title="'Ubah Data'" :id="'edit-modal'" :size="'md'">
    <form style="width: 100%" action="" method="POST" id="edit-form">
        @csrf

        <x-row>
            <input type="hidden" name="id" id="id" value="">

            <x-in-text
                :label="'Nama'"
                :placeholder="'Masukkaan Nama'"
                :col="12"
                :id="'name_edit'"
                :name="'name'"
                value=""
                :required="true">
            </x-in-text>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Apakah anda yakin ingin mengubah data ini?')">Simpan</button>
            </x-col>
        </x-row>
    </form>
</x-modal>
@endsection

@push('js')
<script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

<meta name="url-big" content="{{ route('big.index') }}">
<meta name="url-bi" content="{{ route('bi.index') }}">
<meta name="url-sbi" content="{{ route('sbi.index') }}">
<meta name="url-bi-show" content="{{ route('sbi.show', 'dummy-id') }}">
<meta name="url-sbi-store" content="{{ route('sbi.store', ['id' => 'dummy-id']) }}">
<meta name="url-normal-balances" content="{{ route('get-normal-balance') }}">

<script>
    function edit(id) {
            $('#edit-modal').modal('show');
            $('#edit-form').trigger('reset');
            $('#edit-form').attr('action', '');
            $('#id').val('');

            let url = $('meta[name="url-bi-show"]').attr('content');
            url = url.replace('dummy-id', id);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $('#id').val(data.id);
                    $('#name_edit').val(data.name);

                    //change form action
                    let url = $('meta[name="url-bi-store"]').attr('content');
                    url = url.replace('dummy-id', data.id);
                    $('#edit-form').attr('action', url);

                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        $(document).ready(function() {
            // when edit modal is closed, reset form
            $('#edit-modal').on('hidden.bs.modal', function() {
                $('#edit-form').trigger('reset');
                $('#edit-form').attr('action', '');
                $('#id').val('');
            });

            //on change of report category, get budget item group
            $('#report_category_id_in').on('change', function() {
                let reportCategoryId = $(this).val();
                let url = $('meta[name="url-big"]').attr('content');

                if (reportCategoryId == '') {
                    $('#budget_item_group_id_in').empty();
                    $('#budget_item_group_id_in').append('<option value="">Pilih Kelompok Mata Anggaran</option>');

                    return;
                }

                $('#budget_item_id_in').empty();
                $('#budget_item_id_in').append('<option value="">Pilih Mata Anggaran</option>');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        report_category_id: reportCategoryId
                    },
                    cache: false,
                    success: function(data) {
                        $('#budget_item_group_id_in').empty();
                        $('#budget_item_group_id_in').append('<option value="">Pilih Kelompok Mata Anggaran</option>');

                        data.datas.forEach(function(item) {
                            $('#budget_item_group_id_in').append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        //make using select2

                        $('#budget_item_group_id_in').select2({
                            placeholder: 'Pilih Kelompok Mata Anggaran',
                            allowClear: true
                        });
                    },
                    error: function(data) {
                        alert(data);
                    }
                });
            });

            $('#budget_item_group_id_in').on('change', function() {
                let budgetItemGroupId = $(this).val();

                if (budgetItemGroupId == '') {
                    $('#budget_item_id_in').empty();
                    $('#budget_item_id_in').append('<option value="">Pilih Mata Anggaran</option>');
                    return;
                }

                let url = $('meta[name="url-bi"]').attr('content');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        budget_item_group_id: budgetItemGroupId
                    },
                    cache: false,
                    success: function(data) {
                        $('#budget_item_id_in').empty();
                        $('#budget_item_id_in').append('<option value="">Pilih Mata Anggaran</option>');

                        data.datas.forEach(function(item) {
                            $('#budget_item_id_in').append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        //make using select2

                        $('#budget_item_id_in').select2({
                            placeholder: 'Pilih Mata Anggaran',
                            allowClear: true
                        });
                    },
                    error: function(data) {
                        alert(data);
                    }
                });
            });

            // Get Data Normal Balance

            $.ajax({
                url: $('meta[name="url-normal-balances"]').attr('content'),
                data: {
                    id: "",
                },
                cache: true,
                success: function (data) {
                    data.forEach(function (element) {
                        let option = `<option value="${element.id}">${element.label}</option>`;

                        $('#normal_balance_id_in').append(option);
                    });
                }
            });

        });
</script>

@endpush