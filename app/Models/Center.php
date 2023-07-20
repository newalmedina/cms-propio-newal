<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Center extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "centers";

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
