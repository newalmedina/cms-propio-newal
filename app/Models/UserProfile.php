<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = "user_profiles";

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function center()
    {
        return $this->belongsTo('App\Models\Center', "selected_center", "id");
    }

    public function scopeUsers($query)
    {
        return $query->join('users', 'users.id', '=', 'user_profiles.user_id');
    }

    public function getFullNameAttribute()
    {
        return trim(ucfirst($this->attributes['first_name']) . " " . ucfirst($this->attributes['last_name']));
    }

    public function getBirthdateFormattedAttribute()
    {
        if (!empty($this->birthdate)) {
            return (Carbon::createFromFormat('Y-m-d', $this->birthdate))->format('d/m/Y');
        }

        return '';
    }
}
