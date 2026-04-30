# Dashboard CQRS Demo

## Architecture Decisions

- Symfony 8 (compatible with Symfony 6/7 style) and PHP 8.4.
- Domain-Driven Design (DDD): `App\Domain\Dashboard` contains the domain entity and repository contract.
- CQRS: `DashboardQueryService` implements the read side, while the repository provides page-based record generation.
- Event-Driven Architecture: `PopulateDashboardCommand` is handled by Messenger and publishes `DashboardCacheWarmupCompleted`.
- Asynchronous Processing: the warmup command is dispatched through Symfony Messenger, allowing background cache preparation.
- Caching Layer: `DashboardCacheRepository` stores page results using the configured cache pool.
- Performance Measurement: `DashboardQueryService` tracks query duration and exposes `durationMs` in the view.
- Logging Strategy: cache hits, cache generation, and warmup completion events are logged for observability.

## Setup Instructions

1. Open PowerShell and navigate to the project root:

```powershell
cd e:\wamp64\www\saravanainterview
```

2. Install PHP dependencies:

```powershell
composer install
```

3. Start the application:

```powershell
php -S 127.0.0.1:8000 -t public public/index.php
```

> If you have Symfony CLI, you can also run:
>
> ```powershell
> symfony server:start --no-tls
> ```

## How to Run the Project

Use the following URLs after the server is running.

- Dashboard first page:

  `http://127.0.0.1:8000/dashboard`

- Dashboard with custom page and size:

  `http://127.0.0.1:8000/dashboard/2?pageSize=50`

- Warm up dashboard cache:

  `http://127.0.0.1:8000/dashboard/warmup`

Example curl command:

```powershell
curl -X POST http://127.0.0.1:8000/dashboard/warmup
```

## How to Execute Unit Tests

From the project root, run:

```powershell
vendor\bin\phpunit.bat --configuration phpunit.xml.dist
```

If you use a Unix-style shell or Git Bash, the equivalent is:

```bash
vendor/bin/phpunit --configuration phpunit.xml.dist
```

## Project Notes

- The dataset is simulated in-memory with `100000` records.
- The repository generates only the requested page, not all records, to keep the read model efficient.
- Cached page results are reused for repeated dashboard access.
- `/dashboard/warmup` preloads dashboard pages into cache for faster subsequent reads.
- The project includes logging and performance timing for observability.
