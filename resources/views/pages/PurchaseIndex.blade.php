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
            <x-card-collapsible :title="'Pencarian'" :collapse="true">
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
                        <x-table :thead="['Tanggal', 'Ref', 'Vendor', 'Nama Pemesan', 'Total Harga', 'Status Bayar', 'Status Close', 'Aksi']">
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
                                        @if(Auth::user()->role == 'owner')
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
                    :col="4"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :value="old('branch_id')"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'text'"
                    :label="'User'"
                    :col="4"
                    :id="'in_user'"
                    :name="'user'"
                    :required="true"></x-in-text>
                <x-in-select
                    :label="'Vendor'"
                    :placeholder="'Pilih Vendor'"
                    :col="4"
                    :id="'in_vendor_id'"
                    :name="'vendor_id'"
                    :options="$options['vendors']"
                    :value="old('vendor_id')"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="3"
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

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
    {{-- <meta name="search-user" content="{{ app('request')->input('user_id') ?? null }}"> --}}
    <meta name="search-vendor" content="{{ app('request')->input('vendor_id') ?? null }}">
    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
    <meta name="search-material" content="{{ app('request')->input('material_id') ?? null }}">
    <meta name="search-driver" content="{{ app('request')->input('driver_id') ?? null }}">
    <meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
    <meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">
    <meta name="search-status" content="{{ app('request')->input('status') ?? null }}">

    <meta name="old-branch" content="{{ old('branch_id') ?? null }}">
    <meta name="old-project" content="{{ old('project_id') ?? null }}">
    {{-- <meta name="old-user" content="{{ old('user_id') ?? null }}"> --}}
    <meta name="old-vendor" content="{{ old('vendor_id') ?? null }}">
    <meta name="old-project" content="{{ old('project_id') ?? null }}">
    <meta name="old-material" content="{{ old('material_id') ?? null }}">
    <meta name="old-create" content="{{ old('created') ?? null }}">
    <meta name="old-status" content="{{ old('is_open') ?? null }}">
    <meta name="old-material-price" content="{{ old('material_price') ?? null }}">
    <meta name="old-type" content="{{ old('type') ?? null }}">

    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-material" content="{{ route('material.index') }}">
    <meta name="url-driver" content="{{ route('driver.index') }}">
    <meta name="url-vendor" content="{{ route('vendor.index') }}">

    <meta name="">

    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectVendor = $('#vendor_id');

            let searchVendor = $('meta[name="search-vendor"]').attr('content');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                // let searchUser = $('meta[name="search-project"]').attr('content');
                // let searchDriver = $('meta[name="search-driver"]').attr('content');

                let searchVendor = $('meta[name="search-vendor"]').attr('content');

                if (branchId == '') {
                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');

                    return;
                }

                // Get project
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

                        if (searchUser != '') {
                            selectVendor.val(searchUser).trigger('change');
                        }
                    }
                });
            });

            if (selectBranch.val() != '')
                selectBranch.trigger('change');
        });
    </script>

    {{-- Form --}}
    <script>
        $(function () {
            let selectBranchIn = $('#in_branch_id');
            // let selectVendorIn = $('#in_user_id');
            let selectVendorIn = $('#in_vendor_id');

            selectBranchIn.on('change', function () {
                let branchId = $(this).val();
                let searchVendor = $('meta[name="search-vendor"]').attr('content');

                if (branchId == '') {
                    selectVendorIn.empty();
                    selectVendorIn.append('<option value="">Pilih Vendor</option>');

                    return;
                }

                // Get project
                $.ajax({
                    url: $('meta[name="url-vendor"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        let oldVendor = $('meta[name="old-vendor"]').attr('content');

                        selectVendorIn.empty();
                        selectVendorIn.append(`<option value="">Pilih Vendor</option>`);

                        data.datas.forEach(function(item) {
                            selectVendorIn.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectVendorIn.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Vendor',
                            allowClear: true,
                        });

                        if (oldVendor != '') {
                            selectVendorIn.val(oldVendor).trigger('change');
                        }
                    }
                });

            });

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');
        });
    </script>

    {{-- Functionality for disabled --}}
    <script>
        $(function () {
            const selectType = $('#in_type');
            const oldType = $('meta[name="old-type"]').attr('content');

                if (oldType == 'in') {

                } else if (oldType == 'out') {
                    $( "#in_material_price" ).prop( "disabled", true );
                }

            selectType.on('change', function() {
                if (this.value == 'in' || this.value == '') {
                    $( "#in_material_price" ).prop( "disabled", false );

                } else if (this.value == 'out'){
                    $( "#in_material_price" ).prop( "disabled", true );

                    $( "#in_material_price" ).val('').trigger('change');
                }
            });
        });
    </script>
@endpush
