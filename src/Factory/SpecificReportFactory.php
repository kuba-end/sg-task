<?php

declare(strict_types=1);

namespace App\Factory;

use App\Enum\ReportTypeEnum;
use App\Report\InspectionReport;
use App\Report\MalfunctionReport;
use App\Report\Report;
use App\Report\ReportInterface;
use DateTime;
use InvalidArgumentException;

final class SpecificReportFactory implements SpecificReportFactoryInterface
{
    public function create(ReportInterface $report, string $reportType): ReportInterface
    {
        switch ($reportType) {
            case ReportTypeEnum::MALFUNCTION_TYPE:
                $specificReport = new MalfunctionReport();
                break;
            case ReportTypeEnum::INSPECTION_TYPE:
                $specificReport = new InspectionReport();
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid report type: %s', $reportType));
        }

        $specificReport->setId($report->getId());
        $specificReport->setDescription($report->getDescription());
        $specificReport->setDueDate($report->getDueDate());
        $specificReport->setPhone($report->getPhone());

        return $specificReport;
    }
}
