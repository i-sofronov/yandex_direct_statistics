<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YandexDirectCampaign extends Model
{
    protected $fillable = [
        'account_id', 'campaign_id', 'campaign_name'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(YandexDirectStatistic::class, 'campaign_id', 'campaign_id');
    }
}
