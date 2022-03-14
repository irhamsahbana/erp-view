<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    const STATUS_VOUCHER_URGENT = 1;
    const STATUS_VOUCHER_BY_PLANNING = 2;

    const IS_OPEN_OPEN = 1;
    const IS_OPEN_CLOSE = 0;

    const TYPE_VOUCHER_INCOME = 1;
    const TYPE_VOUCHER_EXPENSE = 2;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
