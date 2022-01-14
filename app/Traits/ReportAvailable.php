<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ReportAvailable
{
    public function reportAvailable(string $report)
    {
        if (!in_array($report, Config::get('export.reports'))) {
            throw new NotFoundHttpException();
        }
    }
}
