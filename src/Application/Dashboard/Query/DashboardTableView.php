<?php

namespace App\Application\Dashboard\Query;

final class DashboardTableView
{
    public function __construct(
        private readonly int $page,
        private readonly int $pageSize,
        private readonly int $totalCount,
        private readonly array $rows,
        private readonly float $durationMs,
        private readonly bool $cacheHit,
        private readonly \DateTimeImmutable $generatedAt,
    ) {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getTotalPages(): int
    {
        return (int) ceil($this->totalCount / $this->pageSize);
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function isCacheHit(): bool
    {
        return $this->cacheHit;
    }

    public function getDurationMs(): float
    {
        return $this->durationMs;
    }

    public function getGeneratedAt(): \DateTimeImmutable
    {
        return $this->generatedAt;
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'pageSize' => $this->pageSize,
            'totalCount' => $this->totalCount,
            'totalPages' => $this->getTotalPages(),
            'rows' => $this->rows,
            'cacheHit' => $this->cacheHit,
            'durationMs' => round($this->durationMs, 2),
            'generatedAt' => $this->generatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['page'], 
            $data['pageSize'], 
            $data['totalCount'], 
            $data['rows'], 
            $data['durationMs'], 
            $data['cacheHit'], 
            new \DateTimeImmutable($data['generatedAt']),
        );
    }
}
