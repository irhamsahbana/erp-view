<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RitBalance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
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
