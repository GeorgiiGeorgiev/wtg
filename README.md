# Housing Offers API

Laravel REST API

Repository: https://github.com/GeorgiiGeorgiev/wtg

## Setup

With Docker running, execute:

```bash
docker compose up -d --build
```

This command performs the complete setup: it creates `.env` from `.env.example`, installs Composer dependencies, generates the application key, starts MySQL and Redis, runs migrations and seeders, and starts the Redis queue worker. No additional setup commands are required. The API will be available at `http://localhost:8080`. The example environment file contains local defaults only and no real secrets.

## Maintenance commands

Run these commands only when intentionally needed:

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan test
docker compose logs -f worker
docker compose restart worker
```

The Docker `worker` service processes queued imports automatically. A separate queue worker command is not required.

To delete all local database data, recreate the schema, and run the seeders:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

## Import idempotency

The database uniquely identifies an import by `supplier_id + external_import_id`. A repeated request returns the existing import and does not queue another job. Offers are uniquely identified by `supplier_id + external_id`; newer imports update the existing offer, while an older `sent_at` value cannot overwrite newer data.

## Safe reservations

Reservation creation runs inside a database transaction and locks the selected offer row with `SELECT ... FOR UPDATE`. Concurrent requests for the same offer are processed one at a time. After the first reservation commits, the next request reads the updated `available_units` value and cannot reserve an unavailable unit.

## Data integrity

Foreign keys intentionally restrict deletion of suppliers that still have imports or offers, and offers that still have reservations. This prevents orphaned import and reservation history. The API does not expose deletion endpoints.
