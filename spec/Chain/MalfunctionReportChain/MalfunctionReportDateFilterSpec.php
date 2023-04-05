<?php

declare(strict_types=1);

namespace spec\App\Chain\MalfunctionReportChain;

use App\Chain\MalfunctionReportChain\MalfunctionReportDateFilter;
use App\Chain\ReportFilterInterface;
use App\Report\MalfunctionReport;
use DateTime;
use PhpSpec\ObjectBehavior;

class MalfunctionReportDateFilterSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MalfunctionReportDateFilter::class);
    }

    function it_should_implements_interface(): void
    {
        $this->shouldImplement(ReportFilterInterface::class);
    }

    function it_filters_date(
        MalfunctionReport $report
    ): void {
        $date = new DateTime('2020-02-05 00:00');
        $report->getDueDate()->willReturn($date);

        $report->setVisitDate($date->format('Y-m-d'))->shouldBeCalled();

        $this->sort($report)->shouldReturn($report);
    }

    function it_returns_report_when_date_not_set(
        MalfunctionReport $report
    ): void {
        $date = new DateTime('2020-02-05 00:00');

        $report->getDueDate()->willReturn(null);

        $report->setVisitDate($date->format('Y-m-d'))->shouldNotBeCalled();

        $this->sort($report)->shouldReturn($report);
    }
}
