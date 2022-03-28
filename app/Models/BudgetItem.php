<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;

    public function reportCategory()
    {
        return $this->belongsTo(Category::class, 'report_category_id');
    }

    public function budgetItemGroup()
    {
        return $this->belongsTo(BudgetItemGroup::class, 'budget_item_group_id');
    }
}
