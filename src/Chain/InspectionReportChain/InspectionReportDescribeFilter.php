<?php

declare(strict_types=1);

namespace App\Chain\InspectionReportChain;

use App\Enum\ReportPriorityEnum;
use App\Enum\ReportTypeEnum;
use App\Report\MalfunctionReport;
use App\Report\ReportInterface;
use FuzzyWuzzy\Fuzz;

final class InspectionReportDescribeFilter
{
    public function sort(ReportInterface $report): ReportInterface
    {
        return $report;
    }
}
