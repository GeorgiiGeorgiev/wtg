# Housing Offers API

Laravel REST API for importing supplier offers, finding the cheapest current offer per property, and safely creating reservations.

## Docker stack

- Nginx
- PHP 8.3 FPM
- MySQL 8.4
- Laravel database queue

Copy `.env.example` to `.env`, generate the application key, and start the stack:

```bash
docker compose run --rm --no-deps app php artisan key:generate
docker compose up -d --build
docker compose exec app php artisan migrate
```

The API will be available at `http://localhost:8080`.

Run the tests with:

```bash
docker compose exec app php artisan test
```
