@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Pengguna'
        ],
    ];
@endphp

@section('content-header', 'Pengguna')

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

                    <x-table :thead="['Cabang', 'Role', 'Username', 'Aksi']">
                        @foreach($datas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->branch->name ?? null }}</td>
                                <td>
                                    @foreach ($options['roles'] as $role)
                                        @if($role['value'] == $data->role)
                                            {{ $role['text'] }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $data->username }}</td>
                                <td>
                                    <a
                                        href="{{ route('user.show', $data->id) }}"
                                        class="btn btn-warning"
                                        title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                    <form
                                        style=" display:inline!important;"
                                        method="POST"
                                        action="{{ route('user.destroy', $data->id) }}">
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
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('user.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="12"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Role'"
                    :placeholder="'Pilih Role'"
                    :col="12"
                    :name="'role'"
                    :options="$options['roles']"
                    :required="true"></x-in-select>
                <x-in-text
                    :label="'Username'"
                    :placeholder="'Masukkan Username'"
                    :col="12"
                    :name="'username'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Password'"
                    :placeholder="'Masukkan Password'"
                    :col="12"
                    :name="'password'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Konfirmasi Password'"
                    :placeholder="'Masukkan Konfirmasi Password'"
                    :col="12"
                    :name="'password_confirmation'"
                    :required="true"></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection
