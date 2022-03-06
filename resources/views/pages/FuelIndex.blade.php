@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Solar'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Solar')

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
                            :col="12"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status'"
                            :placeholder="'Pilih Status'"
                            :col="6"
                            :name="'is_open'"
                            :options="$options['status']"
                            :value="app('request')->input('is_open') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Nomor Kendaraan'"
                            :placeholder="'Masukkaan Nomor Kendaraan'"
                            :col="6"
                            :name="'vehicle_id'"
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
                            <a type="button" class="btn btn-default" href="{{ route('fuel.index') }}">reset</a>
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
                        <x-table :thead="['Tanggal', 'Cabang', 'Nomor Kendaraan', 'Jumlah (Liter)', 'Status', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->vehicle->license_plate }}</td>
                                    <td>{{ $data->amount }}</td>
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
                                                href="{{ route('fuel.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('fuel.destroy', $data->id) }}">
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
                                                action="{{ route('fuel.change-status', $data->id) }}">
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
        <form style="width: 100%" action="{{ route('fuel.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="12"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Nomor Kendaraan'"
                    :placeholder="'Pilih Nomor Kendaraan'"
                    :col="12"
                    :id="'in_vehicle_id'"
                    :name="'vehicle_id'"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="12"
                    :name="'created'"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :step="'0.01'"
                    :label="'Jumlah (Liter)'"
                    :col="12"
                    :name="'amount'"
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
    <meta name="url-vehicle" content="{{ route('vehicle.index') }}">
    <meta name="search-vehicle" content="{{ app('request')->input('vehicle_id') ?? null }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectVehicle = $('#vehicle_id');

            let selectBranchIn = $('#in_branch_id');
            let selectVehicleIn = $('#in_vehicle_id');

            selectBranch.on('change', function() {
                let branchId = $('#branch_id').val();
                let urlVehicle = $('meta[name="url-vehicle"]').attr('content');

                if (branchId == '') {
                    $('#vehicle_id').html('');
                    return;
                }

                $.ajax({
                    url:  `${urlVehicle}?branch_id=${branchId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let vehicle = $('#vehicle_id');
                        let searchVehicle = $('meta[name="search-vehicle"]').attr('content');

                        vehicle.empty();
                        vehicle.append('<option value="">Pilih Nomor Kendaraan</option>');

                        data.datas.forEach(function(item) {
                            vehicle.append('<option value="' + item.id + '">' + item.license_plate + '</option>');
                        });

                        vehicle.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Nomor Kendaraan',
                            allowClear: true
                        });

                        if (searchVehicle != null)
                            vehicle.val(searchVehicle).trigger('change');
                    }
                });
            });

            if (!selectBranch.exists()) {
                let userBranchId = $('meta[name="user-branch"]').attr('content');
                let urlVehicle = $('meta[name="url-vehicle"]').attr('content');

                $.ajax({
                    url:  `${urlVehicle}?branch_id=${userBranchId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let vehicle = $('#vehicle_id');

                        vehicle.empty();
                        vehicle.append('<option value="">Pilih Nomor Kendaraan</option>');

                        data.datas.forEach(function(item) {
                            vehicle.append(`<option value="${item.id}">${item.license_plate}</option>`);
                        });

                        vehicle.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Nomor Kendaraan',
                            allowClear: true
                        });
                    }
                });
            }

            selectBranchIn.on('change', function() {
                let branchId = $('#in_branch_id').val();
                let urlVehicle = $('meta[name="url-vehicle"]').attr('content');

                if (branchId == '') {
                    $('#in_vehicle_id').html('');
                    return;
                }

                $.ajax({
                    url:  `${urlVehicle}?branch_id=${branchId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let vehicle = $('#in_vehicle_id');

                        vehicle.empty();
                        vehicle.append('<option value="">Pilih Nomor Kendaraan</option>');

                        data.datas.forEach(function(item) {
                            vehicle.append('<option value="' + item.id + '">' + item.license_plate + '</option>');
                        });

                        vehicle.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Nomor Kendaraan',
                            allowClear: true
                        });
                    }
                });
            });

            if (!selectBranchIn.exists()) {
                let userBranchId = $('meta[name="user-branch"]').attr('content');
                let urlVehicle = $('meta[name="url-vehicle"]').attr('content');

                $.ajax({
                    url:  `${urlVehicle}?branch_id=${userBranchId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let vehicle = $('#in_vehicle_id');

                        vehicle.empty();
                        vehicle.append('<option value="">Pilih Nomor Kendaraan</option>');

                        data.datas.forEach(function(item) {
                            vehicle.append(`<option value="${item.id}">${item.license_plate}</option>`);
                        });

                        vehicle.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Nomor Kendaraan',
                            allowClear: true
                        });
                    }
                });
            }

            if (selectBranch.val() != '') {
                selectBranch.trigger('change');
            }

        });
    </script>
@endpush
