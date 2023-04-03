<?php

declare(strict_types=1);

namespace App\Factory;

use App\Report\Report;
use App\Report\ReportInterface;
use DateTime;
use Psr\Log\LoggerInterface;

final class ReportFactory implements ReportFactoryInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function create(array $reportData): ReportInterface
    {
        $report = new Report();
        $report->setId($reportData['number']);
        $report->setDescription($reportData['description']);

        $date = $this->resolveDate($reportData);

        $report->setDueDate($date ?? null);
        $report->setPhone($reportData['phone']);

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
