<?php

namespace App\Domain\Dashboard;

final class DashboardRecord
{
    public function __construct(
        private readonly int $id,
        private readonly string $section,
        private readonly int $visits,
        private readonly int $uniqueVisitors,
        private readonly int $conversions,
        private readonly float $conversionRate,
        private readonly \DateTimeImmutable $recordedAt,
    ) {
    }

    public static function create(int $id): self
    {
        $section = sprintf('Page %03d', $id % 500 + 1);
        $visits = 150 + ($id * 13 % 287);
        $uniqueVisitors = max(1, (int) ($visits * (0.35 + (($id % 7) * 0.08))));
        $conversions = max(0, (int) round($visits * (0.01 + (($id % 10) * 0.002))));
        $conversionRate = $conversions > 0 ? $conversions / $visits : 0.0;
        $recordedAt = new \DateTimeImmutable(sprintf('-%d minutes', $id % 1440));

        return new self($id, $section, $visits, $uniqueVisitors, $conversions, $conversionRate, $recordedAt);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSection(): string
    {
        return $this->section;
    }

    public function getVisits(): int
    {
        return $this->visits;
    }

    public function getUniqueVisitors(): int
    {
        return $this->uniqueVisitors;
    }

    public function getConversions(): int
    {
        return $this->conversions;
    }

    public function getConversionRate(): float
    {
        return $this->conversionRate;
    }

    public function getRecordedAt(): \DateTimeImmutable
    {
        return $this->recordedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'section' => $this->section,
            'visits' => $this->visits,
            'uniqueVisitors' => $this->uniqueVisitors,
            'conversions' => $this->conversions,
            'conversionRate' => round($this->conversionRate, 4),
            'recordedAt' => $this->recordedAt->format('Y-m-d H:i:s'),
        ];
    }
}
