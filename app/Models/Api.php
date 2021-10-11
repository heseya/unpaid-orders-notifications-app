<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasUuid;

    protected $fillable = [
        "url",
        "name",
        "version",
        "licence_key",
        "integration_token",
        "refresh_token",
        "uninstall_token",
    ];
}
