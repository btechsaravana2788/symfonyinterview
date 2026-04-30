<?php

namespace App\EventSubscriber;

use App\Application\Dashboard\Event\DashboardCacheWarmupCompleted;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class DashboardCacheWarmupSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DashboardCacheWarmupCompleted::class => 'onCacheWarmupCompleted',
        ];
    }

    public function onCacheWarmupCompleted(DashboardCacheWarmupCompleted $event): void
    {
        $this->logger->notice('Dashboard cache warmup event received.', [
            'pageSize' => $event->getPageSize(),
            'warmupPages' => $event->getWarmupPages(),
            'totalCount' => $event->getTotalCount(),
            'completedAt' => $event->getCompletedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
