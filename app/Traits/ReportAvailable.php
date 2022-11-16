<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ReportAvailable
{
    public function reportAvailable(string $report): void
    {
        if (!$this->isReportAvailable($report)) {
            throw new NotFoundHttpException();
        }
    }

    public function isReportAvailable(string $report): bool
    {
        return in_array($report, Config::get('export.reports'));
}
}
