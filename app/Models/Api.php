<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperApi
 */
class Api extends Model
{
    use HasUuid;

    protected $fillable = [
        'url',
        'version',
        'licence_key',
        'integration_token',
        'refresh_token',
        'uninstall_token',
        'products_updated_at',
        'products_private_updated_at',
        'orders_updated_at',
        'items_updated_at',
    ];

    protected $casts = [
        'products_updated_at' => 'datetime',
        'products_private_updated_at' => 'datetime',
        'orders_updated_at' => 'datetime',
        'items_updated_at' => 'datetime',
    ];

    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class, 'api_id');
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }
}
