<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    use HasUuid;

    protected $fillable = [
        'api_id',
        'store_front_url',
    ];

    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class, 'api_id');
    }
}
