<?php

return [
    'reports' => explode(',', env('EXPORT_REPORTS')),
    'formats' => explode(',', env('EXPORT_FORMATS', 'csv')),
];
