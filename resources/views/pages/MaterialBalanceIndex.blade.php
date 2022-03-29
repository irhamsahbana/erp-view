@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Saldo Material'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Saldo Material')

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
                            :col="4"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Proyek'"
                            :placeholder="'Pilih Proyek'"
                            :col="4"
                            :name="'project_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Material'"
                            :placeholder="'Pilih Material'"
                            :col="4"
                            :name="'material_id'"
                            :required="false"></x-in-select>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('material-mutation.balance') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    <x-col>
                        <x-table :thead="['Cabang', 'proyek', 'Material', 'Kuantitas (Volume)', 'Total Harga Beli']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->material->name }}</td>
                                    <td>{{ number_format($data->qty, 2) }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->total, 2) }}</td>
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
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
    <meta name="search-material" content="{{ app('request')->input('material_id') ?? null }}">

    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-material" content="{{ route('material.index') }}">

    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectMaterial = $('#material_id');

            let searchMaterial = $('meta[name="search-material"]').attr('content');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');
                let searchDriver = $('meta[name="search-driver"]').attr('content');

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

                        if (searchProject != '') {
                            selectProject.val(searchProject).trigger('change');
                        }
                    }
                });
            });

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

            loadMaterial();

            if (searchMaterial != '') {
                loadMaterial();

                $.ajax({
                    type: 'GET',
                    url: `${$('meta[name="url-material"]').attr('content')}/${searchMaterial}`,
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
        });
    </script>
@endpush
