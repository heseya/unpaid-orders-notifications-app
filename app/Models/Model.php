<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Carbon;

class Model extends \Illuminate\Database\Eloquent\Model
{
    // format for database
    public function getDateFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

    // format for responses
    protected function serializeDate(DateTimeInterface $date): string
    {
        // 2019-02-01T03:45:27+00:00
        return Carbon::instance($date)->toIso8601String();
    }
}
