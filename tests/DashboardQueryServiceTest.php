<?php

namespace App\Tests;

use App\Application\Dashboard\Query\DashboardCacheRepository;
use App\Application\Dashboard\Query\DashboardQueryService;
use App\Infrastructure\Dashboard\InMemoryDashboardRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class DashboardQueryServiceTest extends TestCase
{
    public function testFetchDashboardPageCachesThePage(): void
    {
        $cache = new ArrayAdapter();
        $repository = new InMemoryDashboardRepository();
        $cacheRepository = new DashboardCacheRepository($cache);
        $service = new DashboardQueryService($repository, $cacheRepository, new NullLogger());

        $first = $service->fetchDashboardPage(1, 20);

        $this->assertFalse($first->isCacheHit());
        $this->assertSame(20, count($first->getRows()));
        $this->assertSame($repository->getTotalCount(), $first->getTotalCount());

        $second = $service->fetchDashboardPage(1, 20);

        $this->assertTrue($second->isCacheHit());
        $this->assertSame($first->getRows(), $second->getRows());
    }

    public function testFetchDashboardPageRespectsPageBounds(): void
    {
        $cache = new ArrayAdapter();
        $repository = new InMemoryDashboardRepository();
        $cacheRepository = new DashboardCacheRepository($cache);
        $service = new DashboardQueryService($repository, $cacheRepository, new NullLogger());

        $view = $service->fetchDashboardPage(-5, 5);

        $this->assertSame(1, $view->getPage());
        $this->assertSame(10, $view->getPageSize());
        $this->assertSame(100_000, $view->getTotalCount());
        $this->assertSame(10, count($view->getRows()));
    }
}
