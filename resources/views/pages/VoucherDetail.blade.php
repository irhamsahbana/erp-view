@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Voucher'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Voucher')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('voucher.store') }}" method="POST">
                        @csrf
                        @method('POST')

                        <x-row>
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <x-in-select
                                :label="'Cabang'"
                                :placeholder="'Pilih Cabang'"
                                :col="4"
                                :id="'in_branch_id'"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Jenis Voucher'"
                                :placeholder="'Pilih Jenis Voucher'"
                                :col="4"
                                :id="'in_type'"
                                :name="'type'"
                                :options="$options['types']"
                                :value="$data->type"
                                :required="true"></x-in-select>
                            <x-in-select
                                    :label="'Status Voucher'"
                                    :placeholder="'Pilih Status Voucher'"
                                    :col="4"
                                    :id="'in_add_status'"
                                    :name="'status'"
                                    :options="$options['statusVoucher']"
                                    :value="$data->status"
                                    :required="true"></x-in-select>
                            <x-in-select
                                :label="'Order'"
                                :placeholder="'Pilih Order'"
                                :col="4"
                                :id="'in_order_id'"
                                :name="'order_id'"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Jumlah'"
                                :col="4"
                                :id="'in_amount'"
                                :name="'amount'"
                                :value="$data->amount"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="4"
                                :id="'in_created'"
                                :name="'created'"
                                :value="$data->created"
                                :required="true"></x-in-text>
                            <x-in-text
                                :label="'Keterangan'"
                                :col="12"
                                :id="'in_notes'"
                                :name="'notes'"
                                :value="$data->notes"
                                :required="true"></x-in-text>

                            <x-col class="text-left">
                                <a class="btn btn-primary" id="autofill-amount-created" href="javascript:void(0)">
                                    Autofill Jumlah, Tanggal dan Keterangan</a>
                            </x-col>
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

    <meta name="url-order" content="{{ route('order.index') }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">

    <meta name="data-order-id" content="{{ $data->order_id }}">

    {{-- Form --}}
    <script>
        $(function() {
            const selectBranch = $('#in_branch_id');
            const selectType = $('#in_type');
            const selectStatus = $('#in_add_status');

            const autofill = $('#autofill-amount-created');

            selectBranch.on('change', function() {
                const branchId = $(this).val();
                const urlOrder = $('meta[name="url-order"]').attr('content');
                const acceptedOrder = 2;

                $.ajax({
                    url: urlOrder,
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        status: acceptedOrder,
                    },
                    success: function(data) {
                        const selectOrder = $('#in_order_id');
                        const orderId = $('meta[name="data-order-id"]').attr('content');

                        selectOrder.empty();
                        selectOrder.append('<option value="">Pilih Order</option>');

                        data.datas.forEach(function(item) {
                            selectOrder.append(`<option value="${item.id}">${item.ref_no}</option>`);
                        });

                        selectOrder.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Order',
                            allowClear: true
                        });

                        selectOrder.val(orderId).trigger('change');
                    }
                });
            });

            selectType.on('change', function() {
                const type = $(this).val();
                const status = $('#in_add_status');
                const order = $('#in_order_id');


                if (type == 1) {
                    status.prop('disabled', true);
                    order.prop('disabled', true);

                    status.val('');
                    order.val('').trigger('change');
                } else if (type == 2) {
                    status.prop('disabled', false);
                    order.prop('disabled', false);

                    if (status.val() == 1) {
                        order.prop('disabled', true);
                    } else if (status.val() == 2) {
                        order.prop('disabled', false);
                    }
                }
            });

            selectStatus.on('change', function() {
                const status = $(this).val();
                const order = $('#in_order_id');
                const type = $('#in_type').val();

                if (type == 2) {
                    if (status == 1) {
                        order.prop('disabled', false);
                    } else if (status == 2) {
                        order.prop('disabled', false);
                    } else {
                        order.prop('disabled', false);
                    }
                }
            });

            autofill.on('click', function() {
                const type = $('#in_type').val();
                const status = $('#in_add_status').val();
                const order = $('#in_order_id').val();

                let update = confirm('Apakah anda yakin ingin mengubah jumlah, tanggal dan keterangan data ini?');

                if (!update)
                    return;

                if (type == 2 && status == 2 && order != '') {
                    const order = $('#in_order_id').val();
                    let url = $('meta[name="url-order-show"]').attr('content');
                    url = url.replace('dummy-id', order);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            $('#in_amount').val(data.amount);
                            $('#in_created').val(data.created);
                            $('#in_notes').val(data.notes);
                        }
                    });
                } else {
                    $('#in_amount').val('');
                    $('#in_created').val('');
                    $('#in_notes').val('');

                    alert('Hanya bisa autofill jika jenis voucher adalah pengeluaran dan status voucher adalah by planinng, serta order terisi');
                }
            });

            selectBranch.trigger('change');
        });
    </script>
@endpush