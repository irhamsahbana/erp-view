<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtMutation extends Model
{
    use HasFactory;

    const TRANSACTION_TYPE_ADD = 1;
    const TRANSACTION_TYPE_SUBTRACT = 2;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function debtType()
    {
        return $this->belongsTo(Category::class, 'debt_type_id');
    }
}
