<?php

declare(strict_types=1);

namespace spec\App\Chain\MalfunctionReportChain;

use App\Chain\MalfunctionReportChain\MalfunctionReportDescribeFilter;
use App\Chain\ReportFilterInterface;
use App\Enum\ReportPriorityEnum;
use App\Report\InspectionReport;
use App\Report\MalfunctionReport;
use FuzzyWuzzy\Fuzz;
use PhpSpec\ObjectBehavior;

final class MalfunctionReportDescribeFilterSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MalfunctionReportDescribeFilter::class);
    }

    function it_should_implements_interface(): void
    {
        $this->shouldImplement(ReportFilterInterface::class);
    }

    function it_should_not_set_priority_when_not_malfunction_report(
        InspectionReport $report,
        Fuzz $fuzz
    ): void {
        $word = 'aleurwał';

        $report->getDescription()->willReturn($word);
        $fuzz->ratio($word, ReportPriorityEnum::CRITICAL_NEEDLE)->shouldNotBeCalled();
        $fuzz->ratio($word, ReportPriorityEnum::HIGH_NEEDLE)->shouldNotBeCalled();

        $this->sort($report)->shouldReturn($report);
    }

    function it_should_set_critical_priority_when_malfunction_report_and_founds_needle(
        MalfunctionReport $report
    ): void {
        $word1 = 'bardzo';
        $word2 = 'pilne';

        $report->getDescription()->willReturn($word1 . " " . $word2);

        $report->setPriority(ReportPriorityEnum::CRITICAL)->shouldBeCalled();

        $this->sort($report)->shouldReturn($report);
    }

    function it_should_set_normal_priority_when_malfunction_report_and_not_found_correct_combination(
        MalfunctionReport $report
    ): void {
        $word1 = 'jestem';
        $word2 = 'hardkorem';

        $report->getDescription()->willReturn($word1 . " " . $word2);

        $report->setPriority(ReportPriorityEnum::NORMAL)->shouldBeCalled();

        $this->sort($report)->shouldReturn($report);
    }

    function it_should_set_high_priority_when_malfunction_report_and_not_found_correct_combination(
        MalfunctionReport $report
    ): void {
        $word1 = 'wieżowce';
        $word2 = 'pilne';

        $report->getDescription()->willReturn($word1 . " " . $word2);

        $report->setPriority(ReportPriorityEnum::HIGH)->shouldBeCalled();

        $this->sort($report)->shouldReturn($report);
    }
}
