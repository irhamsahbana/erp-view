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
    $year = date('Y');
    $dayCount = cal_days_in_month(CAL_GREGORIAN, app('request')->input('month') ?? 1, $year);
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
                            :col="6"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Bulan'"
                            :placeholder="'Pilih Bulan'"
                            :col="6"
                            :required="true"
                            :name="'month'"
                            :options="$options['month']"
                            :value="app('request')->input('month') ?? null">
                        </x-in-select>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('fuel.report') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    @if (app('request')->input('month'))  
                        <x-col>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="my-auto">Cabang</th>
                                            <th rowspan="2" class="my-auto">No Kendaraan</th>
                                            <th colspan="{{ $dayCount }}"><center>Tanggal</center></th>
                                        </tr>
                                        <tr>
                                            <?php for($n = 1; $n<=$dayCount; $n++) {?>
                                                <th>{{ $n }}</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fuels as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->license_plate }}</td>
                                                <?php for($n = 1; $n<=$dayCount; $n++) {?>
                                                    @if ($n == ltrim(date('d', strtotime($item->created)), '0'))
                                                        <td>{{ $item->amount }} </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                <?php } ?>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-col>
                        <x-col class="d-flex justify-content-end">
                            {{ $fuels->links() }}
                        </x-col>
                        @else
                            <h5>Silahkan isi pencarian terlebih dahulu</h5>
                        @endif

                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
