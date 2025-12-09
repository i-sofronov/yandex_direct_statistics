<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'name', 'type', 'metadata'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function yandex_direct_campaigns(): HasMany
    {
        return $this->hasMany(YandexDirectCampaign::class);
    }

    public function yandex_accesses(): HasMany
    {
        return $this->hasMany(YandexAccess::class);
    }
}
