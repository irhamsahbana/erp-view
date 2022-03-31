<div>
    <div class="mt-5">
        <h4>Sub Jurnal</h4>
        <div class="my-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
        </div>
        @if ($message != null)
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ $message }}
            <button wire:click='resetData' type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if ($message2 != null)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message2 }}
            <button wire:click='resetData' type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <x-table :thead="['Proyek', 'Kelompok MA', 'MA', 'Sub MA', 'Catatan', 'Status', 'Debit', 'Kredit', 'Aksi']">
            <tr>
                <td colspan="10"><h4>Data Asli</h4></td>
            </tr>
            @foreach ($subJournal as $sub)
           
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sub->project->name }}</td>
                <td>{{ $sub->budgetItemGroup->name }}</td>
                <td>{{ $sub->budgetItem->name }}</td>
                <td>{{ $sub->subBudgetItem->name }}</td>
                <td>{{ $jurnal->notes }}</td>
                <td>
                    @if ($sub->is_open == 0)
                    <button class="badge bg-danger border-0">Nonaktif</button>
                    @else
                    <button class="badge bg-success border-0"></button>
                    @endif
                </td>
                @if ($sub->normal_balance_id == $category_debit->id)
                <td>{{ $sub->amount }}</td>
                <td></td>
                @else
                <td></td>
                <td>{{  $sub->amount }}</td>
                @endif
                <td>
                    <button wire:click="edit({{ $sub }}, 'updaterealdata')" class="btn btn-warning" data-toggle="modal" data-target="#edit-modal"><i class="fas fa-edit"></i></button>
                    <button onclick="confirm('Are you sure you want to remove the user from this group?') || event.stopImmediatePropagation()"
                    wire:click='delete({{ $sub->id }}, "deleterealdata")' class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            @endforeach
            @if (count($datahold) > 0)
            <tr>
                <td colspan="9"></td>
            </tr>
                <tr>
                    <td colspan="10"><h4>Data Sementara</h4></td>
                </tr>
                @foreach ($datahold as $sub)
                    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sub->project->name }}</td>
                    <td>{{ $sub->budgetItemGroup->name }}</td>
                    <td>{{ $sub->budgetItem->name }}</td>
                    <td>{{ $sub->subBudgetItem->name }}</td>
                    <td>{{ $jurnal->notes }}</td>
                    <td>
                        @if ($sub->status_data == 'add')
                            <button class="badge bg-primary border-0">{{ ucfirst($sub->status_data) }}</button>
                        @elseif ($sub->status_data == 'delete')
                            <button class="badge bg-danger border-0">{{ ucfirst($sub->status_data) }}</button>
                        @else
                            <button class="badge bg-warning border-0">{{ ucfirst($sub->status_data) }}</button>
                        @endif
                    </td>
                    @if ($sub->normal_balance_id == $category_debit->id)
                    <td>@if ($sub->status_data == 'delete') - @endif{{ $sub->amount }}</td>
                    <td></td>
                    @else
                    <td></td>
                    <td>@if ($sub->status_data == 'delete') - @endif{{ $sub->amount }}</td>
                    @endif
                    <td>
                        @if ($sub->status_data == 'delete' || $sub->status_data == 'edit' )
                        @elseif ($sub->status_data == 'edit')
                        <button wire:click="edit({{ $sub }}, 'edittempdata')" class="btn btn-warning" data-toggle="modal" data-target="#edit-modal"><i class="fas fa-edit"></i></button>
                        @else
                        <button wire:click="edit({{ $sub }}, 'edittempdata')" class="btn btn-warning" data-toggle="modal" data-target="#edit-modal"><i class="fas fa-edit"></i></button>
                        <button onclick="confirm('Are you sure you want to remove the user from this group?') || event.stopImmediatePropagation()"
                        wire:click='delete({{ $sub->id }}, "deletetempdata")' class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="7">Total</td>
                <td>{{ $debit }}</td>
                <td>{{ $kredit }}</td>
                <td></td>
            </tr>

        </x-table>
        <div class="my-2">
            <button type="button" class="btn btn-primary float-right" wire:click='save({{ $datahold }})'>
                <span wire:loading.class='d-none' wire:target='save({{ $datahold }})'>
                    Simpan</i>
                </span>
                <div wire:loading wire:target='save({{ $datahold }})' class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
            </button>
        </div>
    </div>


    {{-- Modal Add  --}}
    <div class="modal fade" id="add-modal" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Sub Jurnal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="hold" style="width: 100%">
                        <div class="row">
                            <div class="col-sm-4 mb-2">
                                <label for="">Kelompok MA</label>
                                <select wire:model='budget_item_group_id' name="budget_item_group" class="form-control"
                                data-live-search="true" required>
                                    <option value="" wire:click='setIdBudgetItemGroup({{ 0 }})'>Pilih Kelompok MA</option>
                                    @foreach ($budgetItemGroup as $big)
                                    <option value="{{ $big->id }}" wire:click='setIdBudgetItemGroup({{ $big->id }})' >{{
                                        $big->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">MA</label>
                                <select wire:model='budget_item_id' name="" id="" class="form-control" data-live-search="true" required>
                                    <option value="" wire:click='setIdBudgetItem({{ 0 }})' selected>Pilih Budget Item</option>
                                    @foreach ($budgetItem as $bi)
                                    <option value="{{ $bi->id }}" wire:click='setIdBudgetItem({{ $bi->id }})'>{{
                                        $bi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Sub MA</label>
                                <select wire:model='sub_budget_item_id' name="" id="" class="form-control" required>
                                    <option value="" selected>Pilih Sub MA</option>
                                    @foreach ($subBudgetItem as $sbi)
                                    <option value="{{ $sbi->id }}">{{ $sbi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Proyek</label>
                                <select wire:model='project_id' name="" id="" class="form-control" required>
                                    <option value="" selected>Pilih Proyek</option>
                                    @foreach ($project as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Posisi</label>
                                <select wire:model='normal_balance_id' name="" id="" class="form-control" required>
                                    <option value="" selected>Pilih Posisi</option>
                                    @foreach ($normalBalance as $nb)
                                    <option value="{{ $nb->id }}">{{ $nb->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Jumlah</label>
                                <input wire:model='amount' type="number" class="form-control" placeholder="Jumlah" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Modal Edit  --}}

    <div class="modal fade" id="edit-modal" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Sub Jurnal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update" style="width: 100%">
                        <div wire:ignore.self class="row">
                            <div class="col-sm-4 mb-2">
                                <label for="">Kelompok MA</label>
                                <select wire:model='budget_item_group_id' name="budget_item_group" class="form-control"
                                    required>
                                    {{-- <option value="" wire:click='editIdBudgetItemGroup({{ 0 }})'>Pilih Budget Item Group</option> --}}
                                    @foreach ($newbudgetItemGroup as $big)
                                    <option value="{{ $big->id }}" wire:click='editIdBudgetItemGroup({{ $big->id }})'>{{
                                        $big->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">MA</label>
                                <select wire:model='budget_item_id' name="" id="" class="form-control" required>
                                    <option value="">Pilih MA</option>
                                    @foreach ($newbudgetItem as $bi)
                                    <option value="{{ $bi->id }}" wire:click='editIdBudgetItem({{ $bi->id }})'>{{
                                        $bi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Sub MA</label>
                                <select wire:model='sub_budget_item_id' name="" id="" class="form-control" required>
                                    <option value="">Pilih Sub Budget Item</option>
                                    @foreach ($newsubBudgetItem as $sbi)
                                    <option value="{{ $sbi->id }}">{{ $sbi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Proyek</label>
                                <select wire:model='project_id' name="" id="" class="form-control" required>
                                    <option value="">Pilih Posisi</option>
                                    @foreach ($project as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Posisi</label>
                                <select wire:model='normal_balance_id' name="" id="" class="form-control" required>
                                    <option value="">Pilih Normal Balance</option>
                                    @foreach ($normalBalance as $nb)
                                    <option value="{{ $nb->id }}">{{ $nb->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <label for="">Jumlah</label>
                                <input wire:model='amount' type="number" class="form-control" placeholder="Jumlah" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>