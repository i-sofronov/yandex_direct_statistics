<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YandexDirectStatistic extends Model
{
    protected $fillable = [
        'campaign_id',
        'date',
        'impressions',
        'clicks',
        'ctr',
        'avg_cpc',
        'cost',
        'conversions',
        'cost_per_conversion',
        'conversion_rate',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(YandexDirectCampaign::class, 'campaign_id', 'campaign_id');
    }
}
