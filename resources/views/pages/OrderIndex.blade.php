@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Order'
        ],
    ];
@endphp

@section('content-header', 'Order')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'">
                <form style="width: 100%">
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="4"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status'"
                            :placeholder="'Pilih Status'"
                            :col="4"
                            :name="'is_open'"
                            :options="$options['status']"
                            :value="app('request')->input('is_open') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status Order'"
                            :placeholder="'Pilih Status Order'"
                            :col="4"
                            :name="'status'"
                            :options="$options['statusOrder']"
                            :value="app('request')->input('status') ?? null"
                            :required="false"></x-in-select>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Mulai'"
                            :col="6"
                            :value="app('request')->input('date_start') ?? null"
                            :name="'date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Selesai'"
                            :col="6"
                            :value="app('request')->input('date_finish') ?? null"
                            :name="'date_finish'"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('order.index') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Tanggal', 'Cabang', 'Pembuat', 'Jumlah', 'Status Order', 'Status', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->user->username }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->amount, 2) }}</td>
                                    <td>
                                        @if($data->status == '1')
                                            Waiting
                                        @elseif($data->status == '2')
                                            Accepted
                                        @elseif($data->status == '3')
                                            Rejected
                                        @elseif($data->status == '4')
                                            Hold
                                        @elseif($data->status == '5')
                                            Closed
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->is_open)
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Close</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->is_open)
                                            <a
                                                href="{{ route('order.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('order.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        @endif
                                        @if(Auth::user()->role == 'owner')
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('order.change-status', $data->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                <button
                                                    type="submit"
                                                    class="btn btn-secondary"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah staus data ini?')"
                                                    title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                            </form>
                                        @endif
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
        <form style="width: 100%" action="{{ route('order.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="4"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'number'"
                    :step="'0.01'"
                    :label="'Jumlah'"
                    :col="4"
                    :name="'amount'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="4"
                    :name="'created'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Keterangan'"
                    :col="12"
                    :name="'notes'"
                    :required="false"></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection
