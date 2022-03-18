<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialMutation extends Model
{
    use HasFactory;

    const TYPE_IN = 1;
    const TYPE_OUT = 2;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
