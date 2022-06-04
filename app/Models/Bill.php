<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    public function bill_vendor() {
        return $this->belongsTo(Bill::class,'bill_vendor_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
