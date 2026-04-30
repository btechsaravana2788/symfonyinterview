<?php

namespace App\Application\Dashboard\Query;

use App\Domain\Dashboard\DashboardRepositoryInterface;
use Psr\Log\LoggerInterface;

final class DashboardQueryService
{
    public function __construct(
        private readonly DashboardRepositoryInterface $repository,
        private readonly DashboardCacheRepository $cacheRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function fetchDashboardPage(int $page, int $pageSize): DashboardTableView
    {
        $page = max(1, $page);
        $pageSize = max(10, min(100, $pageSize));
        $start = microtime(true);

        $cachedView = $this->cacheRepository->getPage($page, $pageSize);
        if ($cachedView !== null) {
            $durationMs = (microtime(true) - $start) * 1000;
            $this->logger->info('Dashboard query cache hit.', ['page' => $page, 'pageSize' => $pageSize, 'durationMs' => $durationMs]);

            return new DashboardTableView(
                $cachedView->getPage(),
                $cachedView->getPageSize(),
                $cachedView->getTotalCount(),
                $cachedView->getRows(),
                $durationMs,
                true,
                new \DateTimeImmutable(),
            );
        }

        $records = $this->repository->getPage($page, $pageSize);
        $rows = array_map(static fn ($record) => $record->toArray(), $records);
        $totalCount = $this->repository->getTotalCount();
        $durationMs = (microtime(true) - $start) * 1000;

        $view = new DashboardTableView(
            $page,
            $pageSize,
            $totalCount,
            $rows,
            $durationMs,
            false,
            new \DateTimeImmutable(),
        );

        $this->cacheRepository->savePage($view);
        $this->logger->info('Dashboard query generated and cached.', ['page' => $page, 'pageSize' => $pageSize, 'durationMs' => $durationMs]);

        return $view;
    }
}
