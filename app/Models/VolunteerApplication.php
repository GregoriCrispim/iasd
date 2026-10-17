<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerApplication extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function choices(): HasMany
    {
        return $this->hasMany(VolunteerApplicationChoice::class);
    }
}
