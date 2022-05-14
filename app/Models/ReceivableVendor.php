<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivableVendor extends Model
{
    use HasFactory;


    protected $table = 'receivable_vendor';
    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

}
