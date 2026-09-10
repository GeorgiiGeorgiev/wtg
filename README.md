# Housing Offers API

Laravel REST API for importing supplier offers, finding the cheapest current offer per property, and safely creating reservations.

Repository: https://github.com/GeorgiiGeorgiev/wtg

## Setup

Copy `.env.example` to `.env`, then run:

```bash
docker compose build
docker compose run --rm --no-deps app composer install
docker compose run --rm --no-deps app php artisan key:generate
docker compose up -d
docker compose exec app php artisan migrate --seed
```

The API will be available at `http://localhost:8080`.

## Commands

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan queue:work --tries=3 --timeout=60
docker compose exec app php artisan test
```

The Docker `worker` service starts the queue worker automatically.

## Import idempotency

The database uniquely identifies an import by `supplier_id + external_import_id`. A repeated request returns the existing import and does not queue another job. Offers are uniquely identified by `supplier_id + external_id`; newer imports update the existing offer, while an older `sent_at` value cannot overwrite newer data.

## Safe reservations

Reservation creation runs inside a database transaction and locks the selected offer row with `SELECT ... FOR UPDATE`. Concurrent requests for the same offer are processed one at a time. After the first reservation commits, the next request reads the updated `available_units` value and cannot reserve an unavailable unit.
