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

## Complete executed commands
Step 1: Install required software

Install these:

PHP 8.2 or higher
Composer
MySQL
Optional: Symfony CLI

Check:

php -v
composer -V
mysql --version
Step 2: Extract project
unzip dashboard-ddd-cqrs-submission.zip
cd dashboard-ddd-cqrs-submission
Step 3: Install project packages
composer install
Step 4: Create MySQL database

Login to MySQL:

mysql -u root -p

Then run:

CREATE DATABASE dashboard_project CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
Step 5: Configure MySQL in Symfony

Open .env.local. If it does not exist:

cp .env .env.local

Add/update this line:

DATABASE_URL="mysql://root:YOUR_PASSWORD@127.0.0.1:3306/dashboard_project?serverVersion=8.0&charset=utf8mb4"

Replace YOUR_PASSWORD with your MySQL password.

Example:

DATABASE_URL="mysql://root:123456@127.0.0.1:3306/dashboard_project?serverVersion=8.0&charset=utf8mb4"
Step 6: Run migrations
php bin/console doctrine:migrations:migrate

Type:

yes
Step 7: Seed 100,000+ records
php bin/console app:seed-dashboard-data
Step 8: Run async worker

Open a second terminal inside the same project folder:

php bin/console messenger:consume async -vv

Keep it running.

Step 9: Start project

Without Symfony CLI:

php -S 127.0.0.1:8000 -t public

Open:

http://127.0.0.1:8000/dashboard
Step 10: Run tests
vendor/bin/phpunit

or:

php bin/phpunit