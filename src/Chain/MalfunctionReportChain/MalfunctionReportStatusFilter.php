<?php

declare(strict_types=1);

namespace App\Chain\MalfunctionReportChain;

use App\Chain\ReportFilterInterface;
use App\Enum\ReportStatusEnum;
use App\Report\ReportInterface;

final class MalfunctionReportStatusFilter implements ReportFilterInterface
{

    public function sort(ReportInterface $report): ReportInterface
    {
        $date = $report->getDueDate();

        if (null === $date) {
            $report->setStatus(ReportStatusEnum::NEW);

            return $report;
        }

        $report->setStatus(ReportStatusEnum::DATE);

        return $report;
    }
}
