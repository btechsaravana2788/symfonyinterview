<?php

namespace App\Tests;

use App\Application\Dashboard\Query\DashboardCacheRepository;
use App\Application\Dashboard\Query\DashboardTableView;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class DashboardCacheRepositoryTest extends TestCase
{
    public function testCacheRepositoryStoresAndReturnsDashboardPages(): void
    {
        $cache = new ArrayAdapter();
        $cacheRepository = new DashboardCacheRepository($cache);
        $view = new DashboardTableView(
            1,
            10,
            100_000,
            [['id' => 1, 'section' => 'Page 1', 'visits' => 100, 'uniqueVisitors' => 70, 'conversions' => 5, 'conversionRate' => 0.05, 'recordedAt' => '2026-01-01 00:00:00']],
            12.3,
            false,
            new \DateTimeImmutable(),
        );

        $cacheRepository->savePage($view);
        $cached = $cacheRepository->getPage(1, 10);

        $this->assertNotNull($cached);
        $this->assertSame($view->toArray(), $cached->toArray());
    }
}
