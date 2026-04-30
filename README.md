# Dashboard CQRS Demo

## Architecture Decisions

- Symfony 8 (compatible with Symfony 6/7 style) and PHP 8.4.
- Domain-Driven Design (DDD): `App\Domain\Dashboard` contains the domain entity and repository contract.
- CQRS: `DashboardQueryService` provides an optimized read model while the repository handles domain data generation.
- Event-Driven Architecture: `PopulateDashboardCommand` is handled by Messenger, and the cache warmup completion is published as `DashboardCacheWarmupCompleted`.
- Asynchronous Processing: Messenger is configured and the warmup command can be dispatched through the message bus.
- Caching Layer: `DashboardCacheRepository` caches read model pages per page and page size.
- Optimized Read Models: `DashboardTableView` returns only a page of rows and caches repeated queries.

## Complete Demo Setup Instructions

1. Open PowerShell and navigate to the project root:

```powershell
cd e:\wamp64\www\saravanainterview
```

2. Install dependencies if not already installed:

```powershell
composer install
```

3. Start the built-in PHP server:

```powershell
php -S 127.0.0.1:8000 -t public public/index.php
```

4. Open the dashboard demo in your browser:

- `http://127.0.0.1:8000/dashboard`

5. Use query parameters to change page or page size:

- `http://127.0.0.1:8000/dashboard/1?pageSize=25`
- `http://127.0.0.1:8000/dashboard/2?pageSize=50`

6. Warm up the dashboard cache using POST:

```powershell
curl -X POST http://127.0.0.1:8000/dashboard/warmup
```

or use an API client like Postman to POST to:

- `http://127.0.0.1:8000/dashboard/warmup`

7. Confirm the route works in a browser or API client.

## How to Run Unit Tests

From the project root run:

```powershell
vendor\bin\phpunit.bat --configuration phpunit.xml.dist
```

## Notes

- The dataset is simulated in-memory with `100000` records.
- The read model is optimized for performance using page-level caching.
- The warmup endpoint demonstrates asynchronous command dispatch and event logging.
