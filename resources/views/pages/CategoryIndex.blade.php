@extends('App')

@php
    $categoryName = '';

    $categories = [
        ['group_by' => 'journal_categories', 'label' => 'Jenis Jurnal', 'icon' => 'fas fa-newspaper'],
        ['group_by' => 'debt_types', 'label' => 'Jenis Mutasi Hutang', 'icon' => 'fas fa-hand-holding-usd'],
    ];

    foreach ($categories as $category) {
        if ($category['group_by'] == app('request')->input('category')) {
            $categoryName = $category['label'];
            break;
        }
    }

    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'List Kategori',
            'href' => route('category.list')
        ],
        [
            'name' => $categoryName
        ],
    ];
@endphp

@section('content-header', $categoryName)

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
                        <x-table :thead="['Nama', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->label }}</td>
                                    <td>
                                        {{-- <a
                                            href="{{ route('category.show', $data->id) }}"
                                            class="btn btn-warning"
                                            title="Ubah"><i class="fas fa-pencil-alt"></i></a> --}}
                                        <a
                                            type="button"
                                            class="btn btn-warning"
                                            title="Edit"
                                            onclick="edit({{ $data->id }})"
                                            href="javascript:void(0)"><i class="fas fa-pencil-alt"></i></a>
                                        <form style=" display:inline!important;" method="POST" action="{{ route('category.destroy', $data->id) }}">
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
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'lg'">
        <form style="width: 100%" action="{{ route('category.store') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="group_by" value="{{ app('request')->input('category') }}">
            <x-row>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama'"
                    :col="12"
                    :name="'label'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Catatan'"
                    :placeholder="'Masukkan Catatan'"
                    :col="12"
                    :name="'notes'"
                    :required="true"></x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal>

    <x-modal :title="'Ubah Kategori'" :id="'modal-edit'" :size="'md'">
        <form style="width: 100%" action="" method="POST" id="form-edit">
            @csrf

            <x-row>
                <input type="hidden" name="id" id="category_id" value="">
                <input type="hidden" name="group_by" value="{{ app('request')->input('category') }}">
                <x-in-text
                    :label="'Nama'"
                    :id="'label_edit'"
                    :name="'label'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Catatan'"
                    :id="'notes_edit'"
                    :name="'notes'"
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-category-show" content="{{ route('category.show', 'dummy-id') }}">
    <meta name="url-category-store" content="{{ route('category.store', ['id' => 'dummy-id']) }}">

    <script>
        function edit(id) {
            $('#modal-edit').modal('show');
            $('#form-edit').trigger('reset');

            let url = $('meta[name="url-category-show"]').attr('content');
            url = url.replace('dummy-id', id);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $('#category_id').val(data.id);
                    $('#label_edit').val(data.label);
                    $('#notes_edit').val(data.notes);

                    //change form action
                    let url = $('meta[name="url-category-store"]').attr('content');
                    url = url.replace('dummy-id', data.id);
                    $('#form-edit').attr('action', url);

                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        $(document).ready(function() {

            $('#modal-edit').on('hidden.bs.modal', function () {
                $('#form-edit').trigger('reset');
                let url = $('meta[name="url-category-store"]').attr('content');
                $('#form-edit').attr('action', url);
            });
        });

    </script>
@endpush
