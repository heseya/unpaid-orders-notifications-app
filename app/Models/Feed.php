<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AuthType;
use App\Enums\FileFormat;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

/**
 * @mixin IdeHelperFeed
 */
class Feed extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'api_id',
        'name',
        'format',
        'auth',
        'username',
        'password',
        'query',
        'refreshed_at',
        'processed_rows',
        'fields',
    ];

    protected $casts = [
        'format' => FileFormat::class,
        'auth' => AuthType::class,
        'refreshed_at' => 'datetime',
        'fields' => 'array',
    ];

    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    public function path(): string
    {
        return "feeds/{$this->getKey()}.csv";
    }

    public function tempPath(): string
    {
        return "feeds-temp/{$this->getKey()}.csv";
    }

    public function url(): string
    {
        return Config::get('app.url') . "/file/{$this->getKey()}";
    }
}
