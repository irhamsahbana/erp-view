<div>
    <x-card-collapsible :title="'Pencarian'" :collapse="true">
        {{-- <form style="width: 100%" action="" method="get">
            @csrf
            @method('get')
            <div class="row">
                <div class="col-sm-4">
                    <label for="">Cabang</label>
                    <select name="branch_id" id="" class="form-control">
                        <option value="">Pilih Cabang</option>
                        @foreach ($branches as $br)
                        <option value="{{ $br->id }}" name="branch_id">{{ $br->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <label for="">Kategori</label>
                    <select name="category_id" id="" class="form-control">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $ct)
                        <option value="{{ $ct->id }}" name="branch_id">{{ $ct->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-2">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form> --}}
    </x-card-collapsible>

    <x-card-collapsible>
        <x-row>
            <x-col class="mb-3">
                @if (session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </x-col>
            <x-col>
                <x-table :thead="['Kelompok Mata Anggaran', 'Mata Anggaran', 'Nama', 'Saldo Normal']">
                    @foreach ($subBudgetItem as $sbi)
                    <tr>
                        <td>{{ 10*(request('page') - 1)+$loop->iteration }}</td>
                        <td>{{ $sbi->budgetItemGroup->name }}</td>
                        <td>{{ $sbi->budgetItem->name }}</td>
                        <td>{{ $sbi->name }}</td>
                        <td>{{ $sbi->normalBalance->label }}</td>
                    </tr>
                    @endforeach
                </x-table>
            </x-col>

            <x-col class="d-flex justify-content-end">
                {{ $subBudgetItem->links() }}
            </x-col>
        </x-row>
    </x-card-collapsible>
</div>