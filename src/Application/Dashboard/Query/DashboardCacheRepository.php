<?php

namespace App\Application\Dashboard\Query;

use Psr\Cache\CacheItemPoolInterface;

final class DashboardCacheRepository
{
    public function __construct(private readonly CacheItemPoolInterface $cache)
    {
    }

    public function getPage(int $page, int $pageSize): ?DashboardTableView
    {
        $item = $this->cache->getItem($this->getCacheKey($page, $pageSize));

        if (!$item->isHit()) {
            return null;
        }

        $data = $item->get();

        if (!is_array($data)) {
            return null;
        }

        return DashboardTableView::fromArray($data);
    }

    public function savePage(DashboardTableView $view): void
    {
        $item = $this->cache->getItem($this->getCacheKey($view->getPage(), $view->getPageSize()));
        $item->set($view->toArray());
        $item->expiresAfter(3600);
        $this->cache->save($item);
    }

    public function clear(): void
    {
        $this->cache->clear();
    }

    private function getCacheKey(int $page, int $pageSize): string
    {
        return sprintf('dashboard.page.%d.%d', $page, $pageSize);
    }
}
