@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Purchase'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Purchase')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'" :collapse="false">
                <form style="width: 100%">
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="6"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Vendor'"
                            :placeholder="'Pilih Vendor'"
                            :col="6"
                            :name="'vendor_id'"
                            :options="$options['vendors']"
                            :value="app('request')->input('vendor_id') ?? null"
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
                            <a type="button" class="btn btn-default" href="{{ route('purchasing.index') }}">reset</a>
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
                        <x-table :thead="['Tanggal', 'Ref', 'Vendor', 'Nama Pemesan', 'Total Harga', 'Status Bayar','Status Diterima', 'Status Close', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->ref_no }}</td>
                                    <td>{{ $data->vendor->name }}</td>
                                    <td>{{ $data->user }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->total, 2) }}</td>
                                    <td>
                                        <form action="{{ route('purchase.change-status-paid', $data->id) }}" style="display:inline!important;" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                                onclick="return confirm('Apakah anda ingin mengubah status pembayaran ini?')"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('purchase.change-status-accept', $data->id) }}" style="display:inline!important;" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-{{ $data->is_accepted == false ? 'danger' : 'success' }}"
                                                onclick="return confirm('Apakah anda ingin mengubah status diterima ini?')"
                                                title="ubah status"><i class="{{ $data->is_accepted == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>
                                        </form>
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
                                            {{-- <a
                                                href="{{ route('material-mutation.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a> --}}
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('purchase.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        @endif
                                        @if(Auth::user()->role == 'owner' || Auth::user()->role == 'admin' || Auth::user()->role == 'purchaser')
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('purchase.change-status', $data->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                <button
                                                    type="submit"
                                                    class="btn btn-secondary"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah staus data ini?')"
                                                    title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                            </form>
                                            <a
                                                href="{{ route('purchase.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="detail"><i class="fas fa-eye"></i></a>
                                        @endif
                                        <a href="{{ route('purchase-detail.print', $data->id) }}" class="btn btn-info"
                                            title="Print"><i class="fas fa-file-alt"></i></a>
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
        <form style="width: 100%" action="{{ route('purchase.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="6"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :value="old('branch_id')"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'text'"
                    :label="'User'"
                    :col="6"
                    :id="'in_user'"
                    :name="'user'"
                    :required="true"></x-in-text>
                <x-in-select
                    :label="'Vendor'"
                    :placeholder="'Pilih Vendor'"
                    :col="6"
                    :id="'in_vendor_id'"
                    :name="'vendor_id'"
                    :options="$options['vendors']"
                    :value="old('vendor_id')"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="6"
                    :id="'in_created'"
                    :name="'created'"
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
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-vendor" content="{{ app('request')->input('vendor_id') ?? null }}">
    <meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
    <meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">

    <meta name="old-branch" content="{{ old('branch_id') ?? null }}">
    <meta name="old-project" content="{{ old('project_id') ?? null }}">
    <meta name="old-vendor" content="{{ old('vendor_id') ?? null }}">

    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-vendor" content="{{ route('vendor.index') }}">

    <meta name="">

    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectVendor = $('#vendor_id');

            let searchVendor = $('meta[name="search-vendor"]').attr('content');

            selectBranch.on('change', function () {
                let branchId = $(this).val();

                let searchVendor = $('meta[name="search-vendor"]').attr('content');

                if (branchId == '') {
                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');

                    return;
                }

                // Get Vendor
                $.ajax({
                    url: $('meta[name="url-vendor"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        selectVendor.empty();
                        selectVendor.append(`<option value="">Pilih Vendor</option>`);

                        data.datas.forEach(function(item) {
                            selectVendor.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectVendor.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Vendor',
                            allowClear: true,
                        });

                        selectVendor.val(searchVendor).trigger('change');
                    }
                });
            });

            if (selectBranch.val() != '')
                selectBranch.trigger('change');
        });
    </script>

@endpush
