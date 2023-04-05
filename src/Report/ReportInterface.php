<?php

namespace App\Report;

use DateTime;

interface ReportInterface
{
    public function getId(): int;

    public function setId(int $id): void;

    public function getDescription(): string;

    public function setDescription(string $description): void;

    public function getDueDate(): ?DateTime;

    public function setDueDate(?DateTime $dueDate): void;

    public function getPhone(): ?string;

    public function setPhone(?string $phone): void;
}
