<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Enum\ReportTypeEnum;
use App\Factory\SpecificReportFactoryInterface;
use App\Report\ReportInterface;
use FuzzyWuzzy\Fuzz;

final class ReportTypeResolver
{
    private SpecificReportFactoryInterface $specificReportFactory;

    public function __construct(
        SpecificReportFactoryInterface $specificReportFactory
    ) {
        $this->specificReportFactory = $specificReportFactory;
    }

    public function resolve(ReportInterface $report): ReportInterface
    {
        $fuzz = new Fuzz();
        $description = explode(' ', $report->getDescription());

        foreach ($description as $word) {
            if ($fuzz->ratio($word, ReportTypeEnum::INSPECTION_TYPE) > 70) {

                return $this->createInspectionReport($report);
            }
        }

        return $this->createMalfunctionReport($report);
    }

    private function createInspectionReport(ReportInterface $report): ReportInterface
    {
        return $this->specificReportFactory->create($report, ReportTypeEnum::INSPECTION_TYPE);
    }

    private function createMalfunctionReport(ReportInterface $report): ReportInterface
    {
        return $this->specificReportFactory->create($report, ReportTypeEnum::MALFUNCTION_TYPE);
    }
}
