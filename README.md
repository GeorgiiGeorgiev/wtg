# Housing Offers API

Laravel REST API for importing supplier offers, finding the cheapest current offer per property, and safely creating reservations.

## Docker stack

- Nginx
- PHP 8.3 FPM
- MySQL 8.4
- Laravel database queue

After Laravel is installed, copy `.env.example` to `.env` and run:

```bash
docker compose up -d --build
```

The API will be available at `http://localhost:8080`.
