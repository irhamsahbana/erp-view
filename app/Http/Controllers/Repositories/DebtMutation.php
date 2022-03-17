<?php

namespace App\Http\Controllers\Repositories;

use App\Models\DebtMutation as Model;
use App\Models\DebtBalance;

class DebtMutation
{
    private Model $model;
    private DebtBalance $balance;
    private $oldAmount = 0;
    private $totalAmount = 0;

    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->setTotalAmount();
        $this->setBalance();
    }

    public function save()
    {
        $this->beforeSave();

        $this->model->save();
        $this->balance->save();
    }

    protected function beforeSave()
    {
        $this->validate();
    }

    protected function validate()
    {
        $this->validateIsOpen();
        $this->validateBalance();
    }

    protected function validateIsOpen()
    {
        if ($this->model->id && !$this->model->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);
    }

    protected function validateBalance()
    {
        $id = $this->model->id;
        $transactionType = $this->model->transaction_type;
        $amount = $this->model->amount;
        $totalAmount = $this->totalAmount;
        $oldAmount = $this->oldAmount;
        $balance = $this->balance;

        if ($transactionType == Model::TRANSACTION_TYPE_ADD) {
            if (!$id)
                $balance->total = $totalAmount + $amount;
            else if ($id && $oldAmount != $amount)
                $balance->total = $totalAmount + $amount - $oldAmount;
        }

        else if ($transactionType == Model::TRANSACTION_TYPE_SUBTRACT) {
            // dd($balance->toArray(), $balance->total);
            if ($totalAmount < $amount || (float) $balance->total <= 0)
                return redirect()->route('debt-mutation.index')->withErrors(['messages' => 'Saldo hutang/piutang kurang dari yang tersedia.']);
                dd('cel');

            if (!$id)
                $balance->total = $totalAmount - $amount;
            else if ($id && $oldAmount != $amount)
                $balance->total = $balance->total - $amount + $oldAmount;

            if ($balance->total < 0)
                return redirect()->back()->withErrors(['messages' => 'Saldo hutang/piutang kurang dari yang tersedia.']);
        }
    }

    protected function setTotalAmount()
    {
        $totalAmount = Model::where('branch_id', $this->model->branch_id)
                            ->where('project_id', $this->model->project_id)
                            ->where('vendor_id', $this->model->vendor_id)
                            ->where('type', $this->model->type)
                            ->get();

        $totalAmountPlus = $totalAmount->where('transaction_type', Model::TRANSACTION_TYPE_ADD)->sum('amount');
        $totalAmountMinus = $totalAmount->where('transaction_type', Model::TRANSACTION_TYPE_SUBTRACT)->sum('amount');
        $totalAmount = $totalAmountPlus - $totalAmountMinus;

        $this->totalAmount = $totalAmount;
    }

    protected function setBalance()
    {
        $balance = DebtBalance::firstOrNew([
            'branch_id' => $this->model->branch_id,
            'project_id' => $this->model->project_id,
            'vendor_id' => $this->model->vendor_id,
            'type' => $this->model->type
        ]);

        $this->balance = $balance;
    }

    public function setOldAmount($oldAmount)
    {
        $this->oldAmount = $oldAmount;
    }
}