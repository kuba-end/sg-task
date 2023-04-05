<?php

declare(strict_types=1);

namespace App\Finder;

final class DuplicateFinder
{
    public function findUniqueByDescription(array $reports): array
    {
        $uniqueReports = [];
        foreach ($reports as $report) {
            $key = $report['description'];
            if (!array_key_exists($key, $uniqueReports)) {
                $uniqueReports[$key] = $report;
            }
        }

        return $uniqueReports;
    }

    public function findDuplicatedByDescription(array $reports): array
    {
        $uniqueReports = [];
        $duplicates = [];
        foreach ($reports as $report) {
            $key = $report['description'];
            if (!array_key_exists($key, $uniqueReports)) {
                $uniqueReports[$key] = $report;
            } else {
                $duplicates[] = $report;
            }
        }

        return $duplicates;
    }
}
