<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBill extends Model
{
    use HasFactory;
    protected $table = 'sub_bills';

    public function bill() {
        return $this->belongsTo(Bill::class,'bill_id');
    }
    public function bill_vendor() {
        return $this->belongsTo(BiilVendor::class,'bill_vendor_id');
    }
    public function bill_item() {
        return $this->belongsTo(BillItem::class,'bill_item_id');
    }
}
