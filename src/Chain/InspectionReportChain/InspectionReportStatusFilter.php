<?php

declare(strict_types=1);

namespace App\Chain\InspectionReportChain;

use App\Chain\ReportFilterInterface;
use App\Enum\ReportStatusEnum;
use App\Report\ReportInterface;

final class InspectionReportStatusFilter implements ReportFilterInterface
{

    public function sort(ReportInterface $report): ReportInterface
    {
        $date = $report->getDueDate();

        if (null === $date) {
            $report->setStatus(ReportStatusEnum::NEW);

            return $report;
        }

        $report->setStatus(ReportStatusEnum::PLANNED);

        return $report;
    }
}
