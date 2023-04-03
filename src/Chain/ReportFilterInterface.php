<?php

namespace App\Chain;

use App\Report\ReportInterface;

interface ReportFilterInterface
{
    public function sort(ReportInterface $report): ReportInterface;
}
