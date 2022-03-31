<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItemGroup extends Model
{
    use HasFactory;

    public function reportCategory()
    {
        return $this->belongsTo(Category::class, 'report_category_id');
    }
}
