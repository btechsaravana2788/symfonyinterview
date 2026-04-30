<?php

namespace App\Application\Dashboard\Event;

final class DashboardCacheWarmupCompleted
{
    public function __construct(
        private readonly int $pageSize,
        private readonly int $warmupPages,
        private readonly int $totalCount,
        private readonly \DateTimeImmutable $completedAt,
    ) {
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getWarmupPages(): int
    {
        return $this->warmupPages;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getCompletedAt(): \DateTimeImmutable
    {
        return $this->completedAt;
    }
}
