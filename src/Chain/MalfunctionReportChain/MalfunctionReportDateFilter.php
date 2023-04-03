<?php

declare(strict_types=1);

namespace App\Chain\MalfunctionReportChain;

use App\Chain\ReportFilterInterface;
use App\Enum\ReportStatusEnum;
use App\Enum\ReportTypeEnum;
use App\Report\ReportInterface;

final class MalfunctionReportDateFilter implements ReportFilterInterface
{

    public function sort(ReportInterface $report): ReportInterface
    {
        $date = $report->getDueDate();

        if (null !== $date){
            $report->setVisitDate($date->format('Y-m-d'));
        }

        return $report;
    }
}
