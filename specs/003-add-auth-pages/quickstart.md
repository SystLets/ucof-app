# Quickstart: Authentication Pages and Profile

## Prerequisites

- Docker and Docker Compose available locally
- Project `.env` configured for application and MongoDB services
- Running on branch `003-add-auth-pages`

## 1. Start application stack

```bash
docker compose up --build -d
```

For local dev profile workflows:

```bash
sh deploy/scripts/local-dev/up.sh -d
```

## 2. Ensure dependencies and schema are ready

```bash
docker compose exec app composer install
docker compose exec app php artisan migrate
```

If initialization commands are required for auth collections/indexes, run them in the app container before testing flows.

## 3. Execute authentication page flows manually

- Open login page and validate successful login redirects to authenticated landing page.
- Attempt invalid credentials and confirm safe, non-sensitive error messaging.
- Execute logout and verify protected routes now require authentication.

## 4. Execute password reset flow

- Open reset request page and submit account email.
- Complete reset using valid token and compliant password.
- Verify old password no longer authenticates.
- Verify expired/reused token is rejected.

## 5. Execute profile flow

- Open profile page while authenticated.
- Verify displayed `name` and `email` match authenticated user.
- Change password with valid current password and confirm success message.
- Attempt change with wrong current password and confirm rejection.
- Verify other active sessions are invalidated after successful password change.

## 6. Run automated tests (container-first)

```bash
docker compose exec app php artisan test
```

Targeted suites (when available):

```bash
docker compose exec app php artisan test --filter=Auth
docker compose exec app php artisan test --filter=Profile
```

## Troubleshooting

- If route behavior is unexpected, clear app caches in container:

```bash
docker compose exec app php artisan optimize:clear
```

- If reset token behavior appears inconsistent, recreate test token and ensure it has not expired or already been marked used.
- If profile data does not match expected identity, confirm current authenticated session context and seeded user data.
- If auth/profile tests fail with `The SCRAM_SHA_256 authentication mechanism requires libmongoc built with ENABLE_SSL`, rebuild the PHP MongoDB extension image with SSL-enabled libmongoc support and rerun tests.
