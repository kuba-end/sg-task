<?php

declare(strict_types=1);

namespace spec\App\Converter;

use App\Converter\PhoneConverter;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

final class PhoneConverterSpec extends ObjectBehavior
{
    function let(LoggerInterface $logger): void
    {
        $this->beConstructedWith($logger);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PhoneConverter::class);
    }

    function it_should_logs_info_and_returns_null_when_phone_empty(LoggerInterface $logger): void
    {
        $report = [
            'number' => 1,
            'phone' => '',
        ];

        $logger->info(sprintf('Report with id %s has empty phone number field', 1))->shouldBeCalled();

        $this->unifyPhoneNumber($report)->shouldReturn(null);
    }

    function it_should_logs_info_and_returns_null_when_phone_not_valid(LoggerInterface $logger): void
    {
        $report = [
            'number' => 1,
            'phone' => 'Gorilla',
        ];

        $logger->info(sprintf(
            'Phone number %s from report with id %s did not seem to be a phone number',
            $report['phone'],
            $report['number']
        ))->shouldBeCalled();

        $this->unifyPhoneNumber($report)->shouldReturn(null);
    }

    function it_should_logs_info_and_returns_unified_number_when_phone_valid(LoggerInterface $logger): void
    {
        $report = [
            'number' => 1,
            'phone' => '+48 505-505-404',
        ];

        $this->unifyPhoneNumber($report)->shouldReturn('505505404');
    }
}
