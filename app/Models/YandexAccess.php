<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YandexAccess extends Model
{
    protected $fillable = [
        'account_id', 'client_login', 'access_token', 'refresh_token', 'token_expires_at', 'is_active', 'metadata'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
