<?php

namespace App\Infrastructure\Dashboard;

use App\Domain\Dashboard\DashboardRecord;
use App\Domain\Dashboard\DashboardRepositoryInterface;

final class InMemoryDashboardRepository implements DashboardRepositoryInterface
{
    public const TOTAL_RECORDS = 100_000;

    public function getTotalCount(): int
    {
        return self::TOTAL_RECORDS;
    }

    /**
     * @return DashboardRecord[]
     */
    public function getPage(int $page, int $pageSize): array
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;
        $end = min(self::TOTAL_RECORDS, $offset + $pageSize);
        $records = [];

        for ($index = $offset + 1; $index <= $end; $index++) {
            $records[] = DashboardRecord::create($index);
        }

        return $records;
    }
}
