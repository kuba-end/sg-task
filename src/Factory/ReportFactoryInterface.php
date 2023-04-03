<?php

namespace App\Factory;

use App\Report\ReportInterface;

interface ReportFactoryInterface
{
    public function create(array $reportData): ReportInterface;
}
