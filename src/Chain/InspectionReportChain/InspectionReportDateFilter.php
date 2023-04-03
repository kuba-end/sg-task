<?php

declare(strict_types=1);

namespace App\Chain\InspectionReportChain;

use App\Chain\ReportFilterInterface;
use App\Enum\ReportStatusEnum;
use App\Enum\ReportTypeEnum;
use App\Report\ReportInterface;

final class InspectionReportDateFilter implements ReportFilterInterface
{

    public function sort(ReportInterface $report): ReportInterface
    {
        $date = $report->getDueDate();

        if (null !== $date){
            $report->setInspectionDate($date->format('Y-m-d'));
            $report->setInspectionWeek($date->format('W'));
        }

        return $report;
    }
}
