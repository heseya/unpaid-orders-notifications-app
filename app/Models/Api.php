<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperApi
 */
final class Api extends Model
{
    protected $fillable = [
        'url',
        'version',
        'licence_key',
        'integration_token',
        'refresh_token',
        'uninstall_token',

        'payment_url',
        'orders_from_days',
    ];
}
