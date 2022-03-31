@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Kelompok Mata Anggaran'
        ],
    ];
@endphp

@section('content-header', 'Kelompok Mata Anggaran')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Jenis Laporan', 'Nama', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->reportCategory->label }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <a
                                            type="button"
                                            class="btn btn-warning"
                                            title="Edit"
                                            onclick="edit({{ $data->id }})"
                                            href="javascript:void(0)"><i class="fas fa-pencil-alt"></i></a>
                                        <form
                                            style=" display:inline!important;"
                                            method="POST"
                                            action="{{ route('big.destroy', $data->id) }}">
                                                @csrf
                                                @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
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
        <form style="width: 100%" action="{{ route('big.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Jenis Laporan'"
                    :placeholder="'Pilih Jenis Laporan'"
                    :col="12"
                    :name="'report_category_id'"
                    :options="$options['reportCategories']"
                    :required="true"></x-in-select>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkaan Nama'"
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

    <x-modal :title="'Ubah Data'" :id="'edit-modal'" :size="'md'">
        <form style="width: 100%" action="" method="POST" id="edit-form">
            @csrf

            <x-row>
                <input type="hidden" name="id" id="id" value="">
                <x-in-select
                    :label="'Jenis Laporan'"
                    :placeholder="'Pilih Jenis Laporan'"
                    :col="12"
                    :id="'report_category_id_edit'"
                    :name="'report_category_id'"
                    :options="$options['reportCategories']"
                    value=""
                    :required="true"></x-in-select>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkaan Nama'"
                    :col="12"
                    :id="'name_edit'"
                    :name="'name'"
                    value=""
                    :required="true"></x-in-text>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin ingin mengubah data ini?')">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection

@push('js')
    <meta name="url-big-show" content="{{ route('big.show', 'dummy-id') }}">
    <meta name="url-big-store" content="{{ route('big.store', ['id' => 'dummy-id']) }}">

    <script>
        function edit(id) {
            $('#edit-modal').modal('show');
            $('#edit-form').trigger('reset');

            let url = $('meta[name="url-big-show"]').attr('content');
            url = url.replace('dummy-id', id);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $('#id').val(data.id);
                    $('#report_category_id_edit').val(data.report_category_id);
                    $('#name_edit').val(data.name);

                    //change form action
                    let url = $('meta[name="url-big-store"]').attr('content');
                    url = url.replace('dummy-id', data.id);
                    $('#edit-form').attr('action', url);

                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        $(document).ready(function() {
            $('#edit-modal').on('hidden.bs.modal', function() {
                $('#edit-form').trigger('reset');
                $('#edit-form').attr('action', '');
                $('#id').val('');
            });
        });
    </script>

@endpush
