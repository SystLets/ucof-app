# Quickstart: Authorization and Access Foundation

## Prerequisites

- Docker and Docker Compose
- Project `.env` configured for MongoDB-backed runtime

## 1. Start services

```bash
docker compose up --build -d
```

For local-dev profile workflows:

```bash
sh deploy/scripts/local-dev/up.sh -d
```

## 2. Run identity migration/initialization

Run migration/bootstrap for identity collections from the app container.

```bash
docker compose exec app php artisan migrate
```

If the implementation uses custom bootstrap command for auth collections, run it as documented by tasks.

## 3. Provision first user through deploy script

Use environment-specific deploy script to add a user.

Example (local-dev):

```bash
sh deploy/scripts/local-dev/add-user.sh
```

Expected behavior:

- prompts or accepts input for identity, role, and credential
- validates input
- creates account or exits with clear duplicate/validation error

## 4. Validate sign-in and dashboard access

- Open sign-in page in browser.
- Authenticate with provisioned user.
- Confirm redirect to dashboard.
- Confirm unauthenticated dashboard access redirects to sign-in.

## 5. Validate password reset flow

- Request password reset for existing account.
- Complete reset with issued token.
- Confirm old credential fails and new credential succeeds.
- Confirm expired/used tokens are rejected.

## 6. Run tests (container-first)

```bash
docker compose exec app php artisan test
```

Targeted auth tests (once added):

```bash
docker compose exec app php artisan test --filter=Auth
```

## Troubleshooting

- If auth initialization changes do not apply, ensure migration/bootstrap steps were run against current container and database state.
- If script fails with duplicate identity, verify user existence before rerunning.
- If reset behavior is inconsistent, verify token expiration and single-use invalidation logic.
