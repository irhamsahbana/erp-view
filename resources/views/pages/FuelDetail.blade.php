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
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('material-mutation.store') }}" method="POST">

                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <x-row>
                            <x-in-select
                                :label="'Cabang'"
                                :placeholder="'Pilih Cabang'"
                                :col="12"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Nomor Kendaraan'"
                                :placeholder="'Pilih Nomor Kendaraan'"
                                :col="12"
                                :name="'vehicle_id'"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="12"
                                :name="'created'"
                                :value="$data->created"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Jumlah (Liter)'"
                                :col="12"
                                :name="'amount'"
                                :value="$data->amount"
                                :required="true"></x-in-text>
                            <x-in-text
                                :label="'Keterangan'"
                                :col="12"
                                :name="'notes'"
                                :value="$data->notes"
                                :required="true"></x-in-text>

                            <x-col class="text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </x-col>
                        </x-row>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-vehicle" content="{{ route('vehicle.index') }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="data-vehicle" content="{{ $data->vehicle_id ?? null}}">

    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectVehicle = $('#vehicle_id');

            selectBranch.on('change', function() {
                let branchId = $('#branch_id').val();
                let urlVehicle = $('meta[name="url-vehicle"]').attr('content');

                if (branchId == '') {
                    $('#vehicle_id').html('');
                    return;
                }

                $.ajax({
                    url:  `${urlVehicle}`,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        branch_id: branchId,
                    },
                    success: function(data) {
                        let vehicle = $('#vehicle_id');
                        let dataVehicle = $('meta[name="data-vehicle"]').attr('content');

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

                        if (dataVehicle != null)
                            vehicle.val(dataVehicle).trigger('change');
                    }
                });
            });

            if (selectBranch.val() != '') {
                selectBranch.trigger('change');
            }
        });
    </script>
@endpush
