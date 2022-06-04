<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillBalance extends Model
{
    use HasFactory;

    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function bill_vendor() {
        return $this->belongsTo(BillVendor::class,'bill_vendor_id');
    }
}
