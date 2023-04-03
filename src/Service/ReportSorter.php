<?php

declare(strict_types=1);

namespace App\Service;

final class ReportSorter
{
    // pomijamy literówki, trzeba by tu jakieś fuzzy search robić, jakby to wrzucić na  elastica to fuzzy mamy ootb
    // thesaurus - dodam słownik z którego ma brać słowa
    private const INSPECTION = ['Przgląd', 'przegląd'];

    public function specifyType(array $report): array
    {
        if ($this->isInspection($report)){
            $report['type'] = 'Przegląd';
            return $report;
        } else {
            $report['type'] = 'Zgłoszenie awarii';
            return $report;
        }
    }
    public function isInspection(array $report): bool
    {
        return $this->containWord($report, self::INSPECTION);
    }

    private function containWord(array $report, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (mb_stripos($report['description'], $needle) === false)
            {
                continue;
            }
            return mb_stripos($report['description'], $needle) !== false;
        }

        return false;
    }
}
