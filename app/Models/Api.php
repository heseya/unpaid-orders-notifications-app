<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $url;
 * @property ?string $name;
 * @property string $version;
 * @property ?string $licence_key;
 * @property string $integration_token;
 * @property string $refresh_token;
 * @property string $uninstall_token;
 *
 * @mixin IdeHelperApi
 */
class Api extends Model
{
    use HasUuid;

    protected $fillable = [
        'url',
        'name',
        'version',
        'licence_key',
        'integration_token',
        'refresh_token',
        'uninstall_token',
    ];

    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class, 'api_id');
    }
}
