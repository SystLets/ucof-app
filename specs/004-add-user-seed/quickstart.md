# Quickstart: User Provisioning Script for Login

## Prerequisites

- Docker and Docker Compose available locally
- Application stack running (app + mongodb containers healthy)
- Running on branch `004-add-user-seed`

## 1. Start application stack

```bash
docker compose up --build -d
```

Or for local-dev profile:

```bash
sh deploy/scripts/local-dev/up.sh -d
```

## 2. Provision a new user (local-dev wrapper)

```bash
sh deploy/scripts/local-dev/add-user.sh "Jane Doe" "jane@example.test" "ValidPassword123"
```

Expected output (success):
```
User jane@example.test provisioned successfully.
```

Expected output (duplicate active account):
```
ERROR: An active account already exists for jane@example.test.
```

Expected output (locked account with same email):
```
ERROR: An active account already exists for jane@example.test.
```

Expected output (weak password):
```
ERROR: Password must be at least 12 characters long.
```

## 3. Provision directly via Artisan (any environment)

```bash
docker compose exec app php artisan ucof:provision-user \
  --name="Jane Doe" \
  --email="jane@example.test" \
  --password="ValidPassword123"
```

## 4. Validate the provisioned user can log in

- Open `http://localhost/login`
- Sign in with provisioned email and password
- Confirm redirect to `/dashboard`

## 5. Reactivate a disabled account

If a user was previously disabled, re-run provisioning with the same email and a new password:

```bash
sh deploy/scripts/local-dev/add-user.sh "Jane Doe" "jane@example.test" "NewValidPassword456"
```

Expected output:
```
User jane@example.test reactivated and password updated.
```

## 6. Run automated provisioning tests (container-first)

```bash
docker compose exec app php artisan test tests/Feature/Provisioning tests/Unit/Provisioning
```

## Troubleshooting

- If the command is not found, clear app caches: `docker compose exec app php artisan optimize:clear`
- If duplicate rejection is unexpected, verify account status in database via Mongo web client (`http://localhost:8081`)
- If wrapper script fails with "argument missing", ensure all three positional arguments are quoted and non-empty
- Wrapper scripts return `1` when argument validation fails and propagate the Artisan exit code otherwise
