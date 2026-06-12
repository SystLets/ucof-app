# Contract: Authentication Interfaces

## Purpose

Define externally visible interface expectations for sign-in, password reset, dashboard access protection, and deploy-time user provisioning.

## Web Route Contract

### Public Routes

- `GET /signin`
  - Returns sign-in page for unauthenticated users.
- `POST /signin`
  - Accepts credential payload.
  - Success: authenticated session established and redirect to dashboard.
  - Failure: generic auth error message without revealing sensitive details.
- `GET /password/reset`
  - Returns password reset request page.
- `POST /password/reset/request`
  - Accepts identity input.
  - Always returns generic success response (no account existence disclosure).
- `POST /password/reset/complete`
  - Accepts reset token and new password.
  - Rejects invalid, expired, or already-used tokens.

### Protected Routes

- `GET /dashboard`
  - Requires authenticated session.
  - Unauthenticated access redirects to sign-in.
- `POST /signout`
  - Requires authenticated session.
  - Invalidates current session.

## Provisioning Script Contract

### Script Location

- `deploy/scripts/<environment>/add-user.sh` (or equivalent environment-specific naming)

### Required Inputs

- `email`
- `role`
- `password` (or secure prompt)

### Behavior

- Validates required inputs and policy constraints.
- Creates new active user account when identity is unique.
- Fails with non-zero exit code on validation errors or duplicates.
- Emits clear operational errors without printing secrets.

### Idempotency

- Re-running with an existing identity must fail safely and leave existing records unchanged.

## Security and Error Semantics

- Authentication errors must be generic and non-enumerating.
- Reset request response must not confirm whether identity exists.
- Sensitive values (passwords/tokens) must never be logged.
