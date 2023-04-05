<?php

declare(strict_types=1);

namespace App\Chain\MalfunctionReportChain;

use App\Chain\ReportFilterInterface;
use App\Enum\ReportPriorityEnum;
use App\Report\MalfunctionReport;
use App\Report\ReportInterface;
use FuzzyWuzzy\Fuzz;

final class MalfunctionReportDescribeFilter implements ReportFilterInterface
{
    public function sort(ReportInterface $report): ReportInterface
    {
        $fuzz = new Fuzz();
        $description = explode(' ', $report->getDescription());

        $this->resolvePriority($report, $fuzz, $description);

        return $report;
    }

    private function resolvePriority(ReportInterface $report, Fuzz $fuzz, array $description): ReportInterface
    {
        if ($report instanceof MalfunctionReport) {
            $criticalSearch = [];
            $highSearch = [];

            foreach ($description as $word) {
                $criticalSearch[] = $fuzz->ratio($word, ReportPriorityEnum::CRITICAL_NEEDLE);
                $highSearch[] = $fuzz->ratio($word, ReportPriorityEnum::HIGH_NEEDLE);
            }

            foreach ($criticalSearch as $value => $score) {
                if ($score > 70 && $highSearch[$value + 1] > 70) {
                    $report->setPriority(ReportPriorityEnum::CRITICAL);

                    return $report;
                }
            }

            foreach ($highSearch as $score) {
                if ($score > 70) {
                    $report->setPriority(ReportPriorityEnum::HIGH);

                    return $report;
                }
            }
            $report->setPriority(ReportPriorityEnum::NORMAL);

            return $report;
        }

        return $report;
    }
}
