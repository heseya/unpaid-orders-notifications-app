<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }
}
