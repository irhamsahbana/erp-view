@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Detail Mutasi Sparepart'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Detail Mutasi Material')

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
                                :col="4"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Proyek'"
                                :placeholder="'Pilih Proyek'"
                                :col="4"
                                :name="'project_id'"
                                :value="$data->project_id"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Material'"
                                :placeholder="'Pilih Material'"
                                :col="4"
                                :name="'material_id'"
                                :value="$data->material_id"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Jenis'"
                                :placeholder="'Pilih Jenis'"
                                :col="3"
                                :id="'in_type'"
                                :name="'type'"
                                :options="$options['types']"
                                :value="$data->type == 1 ? 'in' : 'out'"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Harga Material'"
                                :col="3"
                                :id="'in_material_price'"
                                :name="'material_price'"
                                :value="$data->material_price"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Volume'"
                                :col="3"
                                :name="'volume'"
                                :value="$data->volume"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="3"
                                :name="'created'"
                                :value="$data->created"
                                :required="true"></x-in-text>
                            <x-in-text
                                :label="'Catatan'"
                                :col="12"
                                :id="'in_notes'"
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

    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="data-project" content={{ $data->project_id ?? null }}>
    <meta name="data-material" content={{ $data->material_id ?? null }}>
    <meta name="data-type" content={{ $data->type == 1 ? 'in' : 'out' }}>


    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-material" content="{{ route('material.index') }}">


    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectMaterial = $('#material_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let dataProject = $('meta[name=data-project]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');

                    return;
                }

                // Get project
                $.ajax({
                    url: $('meta[name="url-project"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        selectProject.empty();
                        selectProject.append(`<option value="">Pilih Proyek</option>`);

                        data.datas.forEach(function(item) {
                            selectProject.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectProject.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Proyek',
                            allowClear: true,
                        });

                        if (dataProject != '') {
                            selectProject.val(dataProject).trigger('change');
                        }
                    }
                });
            });

            loadMaterial();

            let dataMaterial = $('meta[name="data-material"]').attr('content');
            if (dataMaterial != '') {
                loadMaterial();

                $.ajax({
                    type: 'GET',
                    url: `${$('meta[name="url-material"]').attr('content')}/${dataMaterial}`,
                }).then(function (data) {
                    let option = new Option(data.data.name, data.data.id, true, true);
                    selectMaterial.append(option).trigger('change');

                    let result = {
                        id: data.data.id,
                        text: data.data.name,
                    };

                    selectMaterial.trigger({
                        type: 'select2:select',
                        params: {
                            data: result
                        }
                    });
                });
            }

            if (selectBranch.val() != '')
                selectBranch.trigger('change');

             // Get material
             function loadMaterial() {
                selectMaterial.select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Material',
                    allowClear: true,
                    async: false,
                    ajax: {
                        url: $('meta[name="url-material"]').attr('content'),
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            let results = data.datas.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                };
                            });

                            return {
                                results: results
                            };
                        },
                        cache: true
                    }
                });
            }
        });
    </script>

    {{-- Functionality for disabled --}}
    <script>
        $(function () {
            const selectType = $('#in_type');
            const type = $('meta[name=data-type]').attr('content');

            if (type == 'in') {

            } else if (type == 'out') {
                $("#in_material_price").prop("disabled", true);
            }

            selectType.on('change', function() {
                if (this.value == 'in' || this.value == '') {
                    $( "#in_material_price" ).prop("disabled", false );

                } else if (this.value == 'out'){
                    $( "#in_material_price" ).prop("disabled", true );

                    $( "#in_material_price" ).val('').trigger('change');
                }
            });
        });
    </script>
@endpush
