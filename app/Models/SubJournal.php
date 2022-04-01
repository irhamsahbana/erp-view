<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubJournal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function budgetItemGroup()
    {
        return $this->belongsTo(BudgetItemGroup::class, 'budget_item_group_id');
    }
    public function budgetItem()
    {
        return $this->belongsTo(BudgetItem::class, 'budget_item_id');
    }
    public function subBudgetItem()
    {
        return $this->belongsTo(SubBudgetItem::class, 'sub_budget_item_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'normal_balance_id');
    }
    public function journal()
    {
        return $this->belongsTo(Journals::class, 'journal_id');
    }
}
