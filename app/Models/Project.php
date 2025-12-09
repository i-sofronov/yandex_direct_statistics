<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name', 'user_id'
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
