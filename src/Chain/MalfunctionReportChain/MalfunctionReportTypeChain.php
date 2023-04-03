<?php

declare(strict_types=1);

namespace App\Chain\MalfunctionReportChain;

use App\Report\ReportInterface;

final class MalfunctionReportTypeChain
{
    public const TAG = 'sg.malfunction_report_type.filter';

    private iterable $filters;

    public function __construct(iterable $filters)
    {
        $this->filters = $filters;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function filter(ReportInterface $report): ReportInterface
    {
        foreach ($this->filters as $filter) {
            $report = $filter->sort($report);
        }

        return $report;
    }
}
