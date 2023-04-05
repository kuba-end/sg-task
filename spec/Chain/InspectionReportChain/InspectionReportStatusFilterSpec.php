<?php

declare(strict_types=1);

namespace spec\App\Chain\InspectionReportChain;

use App\Chain\InspectionReportChain\InspectionReportStatusFilter;
use App\Chain\ReportFilterInterface;
use App\Enum\ReportStatusEnum;
use App\Report\InspectionReport;
use DateTime;
use PhpSpec\ObjectBehavior;

final class InspectionReportStatusFilterSpec extends ObjectBehavior
{

    function it_is_initializable(): void
    {
        $this->shouldHaveType(InspectionReportStatusFilter::class);
    }

    function it_should_implements_interface(): void
    {
        $this->shouldImplement(ReportFilterInterface::class);
    }

    function it_should_set_new_status_when_date_not_set(
        InspectionReport $report
    ): void
    {
        $report->getDueDate()->willReturn(null);

        $report->setStatus(ReportStatusEnum::NEW)->shouldBeCalled();

        $this->sort($report)->shouldReturn($report);
    }

    function it_should_set_planned_status_when_date_set(
        InspectionReport $report
    ): void
    {
        $date = new DateTime('2020-02-05 00:00');

        $report->getDueDate()->willReturn($date);

        $report->setStatus(ReportStatusEnum::PLANNED)->shouldBeCalled();
        $report->setStatus(ReportStatusEnum::NEW)->shouldNotBeCalled();

        $this->sort($report)->shouldReturn($report);
    }
}
