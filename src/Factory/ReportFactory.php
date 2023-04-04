<?php

declare(strict_types=1);

namespace App\Factory;

use App\Converter\PhoneConverter;
use App\Report\Report;
use App\Report\ReportInterface;
use DateTime;
use Psr\Log\LoggerInterface;

final class ReportFactory implements ReportFactoryInterface
{
    private LoggerInterface $logger;

    private PhoneConverter $phoneConverter;

    public function __construct(
        LoggerInterface $logger,
        PhoneConverter $phoneConverter
    ) {
        $this->logger = $logger;
        $this->phoneConverter = $phoneConverter;
    }

    public function create(array $reportData): ReportInterface
    {
        $report = new Report();
        $report->setId($reportData['number']);
        $report->setDescription($reportData['description']);

        $date = $this->resolveDate($reportData);

        $report->setDueDate($date ?? null);
        $report->setPhone($this->phoneConverter->unifyPhoneNumber($reportData['phone']));

        return $report;
    }

    private function resolveDate(array $reportData): ?DateTime
    {
        if (!empty($reportData['dueDate'])) {
                return new DateTime($reportData['dueDate']);
        }

        $this->logger->info(sprintf(
            'Report with id %d has no date set.',
            $reportData['number'],
        ));

        return null;
    }
}
