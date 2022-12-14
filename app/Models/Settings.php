<?php

namespace App\Models;

use App\Traits\HasUuid;
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
        'product_type_set_parent_filter',
        'product_type_set_no_parent_filter',
        'google_custom_label_metatag',
    ];

    protected $casts = [
        'product_type_set_no_parent_filter' => 'boolean',
    ];

    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class, 'api_id');
    }
}
