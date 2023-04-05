<?php

namespace App\Factory;

use App\Report\ReportInterface;

interface SpecificReportFactoryInterface
{
    public function create(ReportInterface $report, string $reportType): ReportInterface;
}
