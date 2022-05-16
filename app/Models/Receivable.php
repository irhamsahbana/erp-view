<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;
    // protected $table = 'receivables';


    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function project() {
        return $this->belongsTo(Project::class,'project_id');
    }
    public function receivable_vendor() {
        return $this->belongsTo(ReceivableVendor::class,'receivable_vendor_id');
    }


}
