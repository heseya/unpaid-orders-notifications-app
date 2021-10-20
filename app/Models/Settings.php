<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settings extends Model
{
    use HasUuid;

    public string $api_id;
    public string $products_url;

    protected $fillable = [
        "api_id",
        "products_url",
    ];

    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class, 'api_id');
    }
}
