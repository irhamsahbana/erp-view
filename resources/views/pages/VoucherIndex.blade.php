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
            <x-card-collapsible :title="'Pencarian'">
                <form style="width: 100%">
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="3"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Jenis Voucher'"
                            :placeholder="'Pilih Jenis Voucher'"
                            :col="3"
                            :name="'type'"
                            :options="$options['types']"
                            :value="app('request')->input('type') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status'"
                            :placeholder="'Pilih Status'"
                            :col="3"
                            :name="'is_open'"
                            :options="$options['status']"
                            :value="app('request')->input('is_open') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status Voucher'"
                            :placeholder="'Pilih Status Voucher'"
                            :col="3"
                            :name="'status'"
                            :options="$options['statusVoucher']"
                            :value="app('request')->input('status') ?? null"
                            :required="false"></x-in-select>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Mulai'"
                            :col="3"
                            :value="app('request')->input('date_start') ?? null"
                            :name="'date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Selesai'"
                            :col="3"
                            :value="app('request')->input('date_finish') ?? null"
                            :name="'date_finish'"></x-in-text>
                        <x-in-text
                            :label="'Keyword'"
                            :col="6"
                            :value="app('request')->input('keyword') ?? null"
                            :name="'keyword'"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('voucher.index') }}">reset</a>
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
                        <x-table :thead="['Tanggal', 'No. Ref', 'Cabang', 'Jenis', 'Status ', 'Keterangan','Jumlah',    'Aksi']">
                            @foreach($datas as $data)
                                <tr>

                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date("d-m-Y", strtotime($data->created)) }}</td>
                                    <td>{{ $data->ref_no }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>
                                        @if($data->type == '1')
                                            Pemasukan
                                        @elseif($data->type == '2')
                                            Pengeluaran
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->status == '1')
                                            Urgent
                                        @elseif($data->status == '2')
                                            By Planning
                                        @endif
                                    </td>
                                    <td>{{ $data->notes }}</td>
                                    <td class="text-right">{{number_format($data->amount) }}</td>

                                    <td>
                                        @if ($data->is_open)
                                            <a
                                                href="{{ route('voucher.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('voucher.destroy', $data->id) }}">
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
                                                action="{{ route('voucher.change-status', $data->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                <button
                                                    type="submit"
                                                    class="btn btn-secondary"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah status data ini?')"
                                                    title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                            </form>
                                        @endif
                                        <a
                                            href="{{ route('voucher.print', $data->id) }}"
                                            class="btn btn-info"
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
        <form style="width: 100%" action="{{ route('voucher.store') }}" method="POST">
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
                <x-in-select
                    :label="'Jenis Voucher'"
                    :placeholder="'Pilih Jenis Voucher'"
                    :col="4"
                    :id="'in_type'"
                    :name="'type'"
                    :options="$options['types']"
                    :required="true"></x-in-select>
                <x-in-select
                        :label="'Status Voucher'"
                        :placeholder="'Pilih Status Voucher'"
                        :col="4"
                        :id="'in_add_status'"
                        :name="'status'"
                        :options="$options['statusVoucher']"
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
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="4"
                    :id="'in_created'"
                    :name="'created'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Keterangan'"
                    :col="12"
                    :id="'in_notes'"
                    :name="'notes'"
                    :required="true"></x-in-text>

                {{-- <x-col class="text-left">
                    <a class="btn btn-primary" id="autofill-amount-created" href="javascript:void(0)">
                        Autofill Jumlah, Tanggal dan Keterangan</a>
                </x-col> --}}
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>

    <x-modal :title="'Ubah Status Voucher'" :id="'modal-change-status'" :size="'md'">
        <form style="width: 100%" action="" method="POST" id="form-status-change">
            @csrf
            @method('PUT')

            <x-row>
                <input type="hidden" name="id" id="order_id" value="">
                <x-in-select
                    :label="'Status Voucher'"
                    :placeholder="'Pilih Status Voucher'"
                    :id="'in_status'"
                    :name="'status'"
                    :options="$options['statusVoucher']"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :id="'info_branch'"
                    :options="$options['branches']"
                    :disabled="true"></x-in-select>
                <x-in-text
                    :label="'Pembuat'"
                    :id="'info_user'"
                    :disabled="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :step="'0.01'"
                    :label="'Jumlah'"
                    :id="'info_amount'"
                    :disabled="true"></x-in-text>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :id="'info_created'"
                    :disabled="true"></x-in-text>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin ingin mengubah status order data ini?')">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="url-order" content="{{ route('order.index') }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">

    {{-- Form --}}
    <script>
        $(function() {
            const selectBranch = $('#in_branch_id');
            const selectType = $('#in_type');
            const selectStatus = $('#in_add_status');
            const selectOrder = $('#in_order_id');

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
                    }
                });
            });

            selectType.on('change', function() {
                const type = $(this).val();
                const status = $('#in_add_status');
                const order = $('#in_order_id');
                const amount = $('#in_amount');
                const created = $('#in_created');
                const notes = $('#in_notes');


                if (type == 1) { // pemasukan
                    status.prop('disabled', true);
                    order.prop('disabled', true);

                    status.val('');
                    order.val('').trigger('change');
                    amount.val('');
                    created.val('');
                    notes.val('');

                    //clear readonly prop
                    amount.prop('readonly', false);
                    created.prop('readonly', false);
                    notes.prop('readonly', false);

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
                const type = $('#in_type').val();
                const order = $('#in_order_id');

                if (type == 2 && status == 1) { // pengeluaran dan urgent
                    order.val('').trigger('change');
                    order.prop('disabled', true);

                    // disable read only created, amount, notes
                    $('#in_created').prop('readonly', false);
                    $('#in_amount').prop('readonly', false);
                    $('#in_notes').prop('readonly', false);
                } else if (type == 2 && status == 2) { // pengeluaran dan by planning
                    order.prop('disabled', false);
                } else { // tidak diisi
                    order.prop('disabled', true);
                }

                $('#in_amount').val('');
                $('#in_created').val('');
                $('#in_notes').val('');
            });

            selectOrder.on('change', function() {
                const orderId = $(this).val();
                let urlOrder = $('meta[name="url-order-show"]').attr('content');

                if (orderId == '')
                    return;

                urlOrder = urlOrder.replace('dummy-id', orderId);


                $.ajax({
                    url: urlOrder,
                    type: 'GET',
                    data: {
                        id: orderId,
                    },
                    success: function(data) {
                        const amount = $('#in_amount');
                        const created = $('#in_created');
                        const notes = $('#in_notes');

                        amount.val(data.amount);
                        created.val(data.created);
                        notes.val(data.notes);

                        //make readonly for field above
                        amount.prop('readonly', true);
                        created.prop('readonly', true);
                        notes.prop('readonly', true);
                    },
                    error: function(err) {
                        amount.val('');
                        created.val('');
                        notes.val('');

                        //show error in alert
                        alert(err.responseJSON.message);

                    }
                });
            });
        });
    </script>
@endpush
<style>
    th {
      text-align: center;
    }
</style>
