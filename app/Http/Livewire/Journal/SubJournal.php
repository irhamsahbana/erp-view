<?php

namespace App\Http\Livewire\Journal;

use Error;
use App\Models\Project;
use Livewire\Component;
use App\Models\Category;
use App\Models\Journals;
use App\Models\BudgetItem;
use App\Models\SubBudgetItem;
use App\Models\BudgetItemGroup;
use App\Models\TemporarySubJurnal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SubJournal as ModelSubJournal;
use Livewire\WithPagination;

class SubJournal extends Component
{
    use WithPagination;
    public $datahold, $category_debit, $category_kredit, $update_action, $id_sub, $jurnal, $branchIdJournal, $subJournal, $budgetItemGroup, $budgetItem, $subBudgetItem, $project, $normalBalance, $da, $budget_item_group, $idBudgetItemGroup, $idBudgetItem, $budget_item_group_id, $budget_item_id, $sub_budget_item_id, $project_id, $normal_balance_id, $amount, $notes, $message, $message2, $newbudgetItemGroup, $newbudgetItem, $newsubBudgetItem, $datadebit, $datakedit, $datadebit_temp, $datakredit_temp, $kreditAddTemp, $debitAddTemp, $kreditEditTemp, $debitEditTemp, $kreditDeleteTemp, $debitDeleteTemp, $debit, $kredit;

    protected $listeners = [
        'ulang' => 'ulang'
    ];
    public function mount($subjournal, $journal)
    {
        $this->subJournal = $subjournal;
        $this->branchIdJournal = $journal->branch_id;
        $this->jurnal = $journal;
    }
    public function render()
    {

        $this->budgetItemGroup = BudgetItemGroup::all();
        $this->budgetItem = BudgetItem::where('budget_item_group_id', $this->idBudgetItemGroup)->get();
        $this->subBudgetItem = SubBudgetItem::where('budget_item_id', $this->idBudgetItem)->where('budget_item_group_id', $this->idBudgetItemGroup)->get();
        $this->project = Project::where('branch_id', $this->branchIdJournal)->get();
        $this->normalBalance = Category::where('group_by', 'normal_balances')->get();
        $this->newbudgetItemGroup = BudgetItemGroup::all();
        $this->newbudgetItem = BudgetItem::where('budget_item_group_id', $this->budget_item_group_id)->get();
        $this->newsubBudgetItem = SubBudgetItem::where('budget_item_id', $this->budget_item_id)->where('budget_item_group_id', $this->budget_item_group_id)->get();
        $this->datahold = TemporarySubJurnal::where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        $this->category_debit = Category::where('slug', 'debit')->first();
        $this->category_kredit = Category::where('slug', 'kredit')->first();

        // $this->datadebit = ModelSubJournal::where('normal_balance_id', $this->category_debit->id)->get();
        // $this->datakredit = ModelSubJournal::where('normal_balance_id', $this->category_kredit->id)->get();
        // $this->datadebit_temp = TemporarySubJurnal::where('normal_balance_id', $this->category_debit->id)->get();
        // $this->datakredit_temp = TemporarySubJurnal::where('normal_balance_id', $this->category_kredit->id)->get();
        $this->kredit = $this->countKredit();
        $this->debit = $this->countDebit();
        $this->subJournal = collect($this->subJournal);
        return view('livewire.journal.sub-journal');
    }
    public function countDebit()
    {
        $editTempDebit = TemporarySubJurnal::where('status_data', 'update')->where('normal_balance_id', $this->category_debit->id)->where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        $addTempDebit = TemporarySubJurnal::where('status_data', 'add')->where('normal_balance_id', $this->category_debit->id)->where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        $deleteTempDebit = TemporarySubJurnal::where('status_data', 'delete')->where('normal_balance_id', $this->category_debit->id)->where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        
        $jumedit = 0;
        $jumadd = 0;
        $jumdelete = 0;

        foreach($editTempDebit as $edt){
            $jumedit += $edt->amount;
        }
        foreach($addTempDebit as $adt){
            $jumadd += $adt->amount;
        }
        foreach($deleteTempDebit as $ddt){
            $jumdelete += $ddt->amount;
        }

        $totalDebitTemp = $jumadd + $jumedit;
        $totalDebitReal = 0;
        $debitReal = ModelSubJournal::where('normal_balance_id', $this->category_debit->id)->where('journal_id', $this->jurnal->id)->get();
        foreach ($debitReal as $real) {
            $totalDebitReal += $real->amount;
        }
        $total = $totalDebitTemp + $totalDebitReal;
        return $total;
    }
    public function countKredit()
    {
        $editTempKredit = TemporarySubJurnal::where('status_data', 'update')->where('normal_balance_id', $this->category_kredit->id)->where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        $addTempKredit = TemporarySubJurnal::where('status_data', 'add')->where('normal_balance_id', $this->category_kredit->id)->where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        $deleteTempKredit = TemporarySubJurnal::where('status_data', 'delete')->where('normal_balance_id', $this->category_kredit->id)->where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->get();
        
        $jumedit = 0;
        $jumadd = 0;
        $jumdelete = 0;

        foreach($editTempKredit as $edt){
            $jumedit += $edt->amount;
        }
        foreach($addTempKredit as $adt){
            $jumadd += $adt->amount;
        }
        foreach($deleteTempKredit as $ddt){
            $jumdelete += $ddt->amount;
        }

        $totalKreditTemp = $jumadd + $jumedit;

        $totalKreditReal = 0;
        // $totalKreditTemp = $this->kreditAddTemp + $this->kreditEditTemp - $this->kreditDeleteTemp;
        $kreditReal = ModelSubJournal::where('normal_balance_id', $this->category_kredit->id)->where('journal_id', $this->jurnal->id)->get();
        foreach ($kreditReal as $real) {
            $totalKreditReal += $real->amount;
        }
        $total = $totalKreditTemp + $totalKreditReal;
        return $total;
    }
    public function setIdBudgetItemGroup($value)
    {
        $this->idBudgetItemGroup = $value;
        // $this->emit('ulang');
    }
    public function setIdBudgetItem($value)
    {
        $this->idBudgetItem = $value;
        // $this->emit('ulang');
    }
    public function editIdBudgetItemGroup($value)
    {
        $this->idBudgetItemGroup = $value;
        // $this->emit('ulang');
    }
    public function editIdBudgetItem($value)
    {
        $this->idBudgetItem = $value;
        // $this->emit('ulang');
    }
    public function tes()
    {
        dd('Hello');
    }
    public function hold()
    {
        $sub = $this->validate([
            'budget_item_group_id' => 'required',
            'budget_item_id' => 'required',
            'sub_budget_item_id' => 'required',
            'project_id' => 'required',
            'normal_balance_id' => 'required',
            'amount' => 'required|numeric',
        ]);
        $sub['journal_id'] = $this->jurnal->id;
        $sub['status_data'] = 'add';
        $sub['user_id'] =  Auth::user()->id;
        DB::beginTransaction();
        try {
            TemporarySubJurnal::create($sub);
            DB::commit();
            $this->resetData();
            $this->emit('ulang');
            return redirect()->route('detail.journal', ['journal' => $this->jurnal->id]);
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function save($datahold)
    {
        // DB::beginTransaction();
        // try {
        //     $datahold = (object)$datahold;
        //     foreach ($datahold as $data) {
        //         $data = (object)$data;
        //         $sub = [
        //             'budget_item_group_id' => $data->budget_item_group_id,
        //             'budget_item_id' => $data->budget_item_id,
        //             'sub_budget_item_id' => $data->sub_budget_item_id,
        //             'project_id' => $data->project_id,
        //             'normal_balance_id' => $data->normal_balance_id,
        //             'amount' => $data->amount,
        //             'notes' => $data->notes,
        //             'journal_id' => $data->journal_id,
        //         ];
        //         ModelSubJournal::create($sub);
        //     }
        //     TemporarySubJurnal::truncate();
        //     DB::commit();
        //     $this->resetData();
        //     return redirect()->route('detail.journal', ['journal' => $this->jurnal->id]);
        // } catch (Error $e) {
        //     DB::rollBack();
        //     dd($e);
        // }
        // $count_debit = 0;
        // $count_kredit = 0;
        // $count_debit_temp = 0;
        // $count_kredit_temp = 0;

        // foreach($this->datadebit as $debit){
        //     $count_debit += $debit->amount;
        // };

        // foreach($this->datakredit as $kredit){
        //     $count_kredit += $kredit->amount;
        // };
        // foreach($this->datadebit_temp as $debit){
        //     $count_debit_temp += $debit->amount;
        // };

        // foreach($this->datakredit_temp as $kredit){
        //     $count_kredit_temp += $kredit->amount;
        // };

        // $debit_total = $count_debit + $count_debit_temp;
        // $kredit_total = $count_kredit + $count_kredit_temp;
        // dd($this->kreditAddTemp, $this->debitAddTemp, $this->kreditEditTemp, $this->debitEditTemp, $this->kreditDeleteTemp, $this->debitDeleteTemp, $this->category_debit->id);
        $debit_total = $this->countDebit();
        $kredit_total = $this->countKredit();

        if($debit_total != $kredit_total){
            $this->message2 = "Data tidak bisa di update, Kredit dan Debit Tidak Sama";
        }else{
            $datahold = (object)$datahold;
            foreach ($datahold as $data) {
                $data = (object)$data;
                $sub = [
                    'budget_item_group_id' => $data->budget_item_group_id,
                    'budget_item_id' => $data->budget_item_id,
                    'sub_budget_item_id' => $data->sub_budget_item_id,
                    'project_id' => $data->project_id,
                    'normal_balance_id' => $data->normal_balance_id,
                    'amount' => $data->amount,
                    'journal_id' => $data->journal_id,
                    'user_id' => $data->user_id,
                ];
                if($data->status_data == 'delete')
                {
                    ModelSubJournal::where('id', $data->id)->delete();
                }else {
                    # code...
                    ModelSubJournal::create($sub);
                }
            }
            TemporarySubJurnal::where('journal_id', $this->jurnal->id)->where('user_id', Auth::user()->id)->delete();
            $this->resetData();
            return redirect()->route('detail.journal', ['journal' => $this->jurnal->id]);
        }
    }
    
    public function delete($id, $action)
    {
        DB::beginTransaction();
        try {
            if($action == 'deleterealdata'){

                ModelSubJournal::where('id', $id)->delete();
                $this->message = 'Sub Jurnal Berhasil Di Hapus';
            }else{
                TemporarySubJurnal::where('id', $id)->delete();
                $this->message2 = 'Sub Jurnal Berhasil Di Hapus';
            }
            DB::commit();
            $this->emit('ulang');
            // return redirect()->route('edit.journal', ['journal' => $this->jurnal->id]);
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function edit($datasub, $action)
    {
        // dd($project_id, $budget_item_group_id, $budget_item_id, $sub_budget_item_id, $normal_balance_id, $amount, $notes);
        $datasub = (object)$datasub;
        // dd($datasub->budget_item_id);
        $this->update_action = $action;
        $this->id_sub = $datasub->id;
        $this->budget_item_group_id = $datasub->budget_item_group_id;
        $this->budget_item_id = $datasub->budget_item_id;
        $this->sub_budget_item_id = $datasub->sub_budget_item_id;
        $this->project_id = $datasub->project_id;
        $this->normal_balance_id = $datasub->normal_balance_id;
        $this->amount = $datasub->amount;
        $this->notes = $datasub->notes;

        $this->newbudgetItemGroup = BudgetItemGroup::all();
        $this->newbudgetItem = BudgetItem::where('budget_item_group_id', $this->budget_item_group_id)->get();
        $this->newsubBudgetItem = SubBudgetItem::where('budget_item_id', $this->budget_item_id)->where('budget_item_group_id', $this->budget_item_group_id)->get(); 

    }
    public function update()
    {
        $sub = $this->validate([
            'budget_item_group_id' => 'required',
            'budget_item_id' => 'required',
            'sub_budget_item_id' => 'required',
            'project_id' => 'required',
            'normal_balance_id' => 'required',
            'amount' => 'required|numeric',
        ]);
        $sub['journal_id'] = $this->jurnal->id;
        $sub['id_process'] = $this->jurnal->id;
        $sub['status_data'] = 'update';
        $sub['user_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            if($this->update_action == 'updaterealdata')
            {
                ModelSubJournal::where('id', $this->id_sub)->delete();
                TemporarySubJurnal::create($sub);
            }else{
                TemporarySubJurnal::where('id', $this->id_sub)->update($sub);
            }
            $this->message = 'Data Jurnal Berhasil Di Hapus';
            DB::commit();
            return redirect()->route('detail.journal', ['journal' => $this->jurnal->id])->with('message', $this->message);
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }     
    public function resetData()
    {
        $this->budget_item_group_id = null;
        $this->budget_item_id = null;
        $this->sub_budget_item_id = null;
        $this->project_id = null;
        $this->normal_balance_id = null;
        $this->amount = null;
        $this->notes = null; 
        $this->message = null;
        $this->message2 = null;
        $this->update_action = null;
    }   
    public function ulang()
    {

    }
}
