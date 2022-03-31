<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journals extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'journal_category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
