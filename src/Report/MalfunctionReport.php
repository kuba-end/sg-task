<?php

declare(strict_types=1);

namespace App\Report;

use App\Enum\ReportTypeEnum;
use DateTime;

class MalfunctionReport implements ReportInterface
{
    private int $id;

    public string $description;

    public string $type = ReportTypeEnum::MALFUNCTION_TYPE;

    public ?string $priority = null;

    private ?DateTime $dueDate;

    public string $visitDate;

    public string $status;

    public ?string $recommendation = null;

    public ?string $phone;

    public ?DateTime $createdAt = null;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(?string $priority): void
    {
        $this->priority = $priority;
    }

    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getVisitDate(): string
    {
        return $this->visitDate;
    }

    public function setVisitDate(string $visitDate): void
    {
        $this->visitDate = $visitDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function setRecommendation(?string $recommendation): void
    {
        $this->recommendation = $recommendation;
    }
}
