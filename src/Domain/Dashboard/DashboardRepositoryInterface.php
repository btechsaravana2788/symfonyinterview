<?php

namespace App\Domain\Dashboard;

interface DashboardRepositoryInterface
{
    public function getTotalCount(): int;

    /**
     * @return DashboardRecord[]
     */
    public function getPage(int $page, int $pageSize): array;
}
