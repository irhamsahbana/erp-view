<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RitMutation extends Model
{
    use HasFactory;

    const TRANSACTION_TYPE_ADD = 1;
    const TRANSACTION_TYPE_SUBTRACT = 2;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function materialMutation()
    {
        return $this->belongsTo(MaterialMutation::class);
    }
}
