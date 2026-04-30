<?php

namespace App\Application\Dashboard\Handler;

use App\Application\Dashboard\Command\PopulateDashboardCommand;
use App\Application\Dashboard\Event\DashboardCacheWarmupCompleted;
use App\Application\Dashboard\Query\DashboardCacheRepository;
use App\Application\Dashboard\Query\DashboardQueryService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
final class PopulateDashboardCommandHandler
{
    public function __construct(
        private readonly DashboardQueryService $dashboardQueryService,
        private readonly DashboardCacheRepository $cacheRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(PopulateDashboardCommand $command): void
    {
        $pageSize = max(10, min(100, $command->getPageSize()));
        $pages = max(1, min(200, $command->getWarmupPages()));

        for ($page = 1; $page <= $pages; $page++) {
            $view = $this->dashboardQueryService->fetchDashboardPage($page, $pageSize);
            $this->cacheRepository->savePage($view);
        }

        $event = new DashboardCacheWarmupCompleted(
            $pageSize,
            $pages,
            $this->cacheRepository->getPage(1, $pageSize)?->getTotalCount() ?? 0,
            new \DateTimeImmutable(),
        );

        $this->eventDispatcher->dispatch($event);
        $this->logger->info('Dashboard cache warmup completed.', ['pageSize' => $pageSize, 'pages' => $pages]);
    }
}
