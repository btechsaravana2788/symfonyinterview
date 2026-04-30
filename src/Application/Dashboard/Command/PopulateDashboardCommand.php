<?php

namespace App\Application\Dashboard\Command;

final class PopulateDashboardCommand
{
    public function __construct(
        private readonly int $pageSize = 50,
        private readonly int $warmupPages = 20,
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
}
