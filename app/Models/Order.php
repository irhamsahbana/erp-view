<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_ORDER_WAITING = 1;
    const STATUS_ORDER_ACCEPTED = 2;
    const STATUS_ORDER_REJECTED = 3;
    const STATUS_ORDER_HOLD = 4;

    const IS_OPEN_OPEN = 1;
    const IS_OPEN_CLOSE = 0;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
