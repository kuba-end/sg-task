<?php

declare(strict_types=1);

namespace spec\App\Chain\InspectionReportChain;

use App\Chain\InspectionReportChain\InspectionReportDateFilter;
use App\Chain\ReportFilterInterface;
use App\Report\InspectionReport;
use DateTime;
use PhpSpec\ObjectBehavior;

class InspectionReportDateFilterSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(InspectionReportDateFilter::class);
    }

    function it_should_implements_interface(): void
    {
        $this->shouldImplement(ReportFilterInterface::class);
    }

    function it_filters_date(
        InspectionReport $report
    ): void {
        $date = new DateTime('2020-02-05 00:00');
        $report->getDueDate()->willReturn($date);

        $report->setInspectionDate($date->format('Y-m-d'))->shouldBeCalled();
        $report->setInspectionWeek($date->format('W'))->shouldBeCalled();

        $this->sort($report)->shouldReturn($report);
    }

    function it_returns_report_when_date_not_set(
        InspectionReport $report
    ): void {
        $date = new DateTime('2020-02-05 00:00');

        $report->getDueDate()->willReturn(null);

        $report->setInspectionDate($date->format('Y-m-d'))->shouldNotBeCalled();
        $report->setInspectionWeek($date->format('W'))->shouldNotBeCalled();

        $this->sort($report)->shouldReturn($report);
    }
}
