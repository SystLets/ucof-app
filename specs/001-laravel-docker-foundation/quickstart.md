# Quickstart: Laravel Docker Foundation

## Prerequisites

- Docker and Docker Compose available on the host
- No host PHP, Composer, or MongoDB installation required to run the stack

## Start Local Development

1. Start the full local stack:

```bash
docker compose up --build
```

2. Start the local-dev variant with MongoDB web client access:

```bash
docker compose --profile local-dev up --build
```

3. Open the application through nginx in the browser.

4. Verify healthchecks:

```bash
curl http://localhost/health
```

## Run Tests

Run the project test suite inside the application container:

```bash
docker compose exec app php artisan test
```

If needed, execute PHPUnit directly:

```bash
docker compose exec app vendor/bin/phpunit
```

## Inspect MongoDB in local-dev

Use the MongoDB web client included in the local-dev setup.

Open:

```text
http://localhost:8081
```

## Environment Support

- `deploy/specs/local-dev/` and `deploy/scripts/local-dev/` for local development support
- `deploy/specs/staging/` and `deploy/scripts/staging/` for staging support
- `deploy/specs/production/` and `deploy/scripts/production/` for production support
