# Contract: Authentication Pages and Profile Interfaces

## Purpose

Define externally visible interface behavior for login, reset password, logout, authenticated landing, and profile/password-change flows.

## Web Route Contract

### Guest-Only Routes

- `GET /login`
  - Returns login page for unauthenticated users.
  - Authenticated users are redirected to landing page.

- `POST /login`
  - Accepts credential payload (`email`, `password`, optional remember flag).
  - Success: authenticated session established; redirect to landing route.
  - Failure: generic auth error response without identity leakage.

- `GET /password/reset`
  - Returns password reset request page.

- `POST /password/reset/request`
  - Accepts identity input (`email`).
  - Always returns generic acknowledgment regardless of account existence.

- `GET /password/reset/{token}`
  - Returns reset completion page for token input context.

- `POST /password/reset/complete`
  - Accepts token + new password + confirmation.
  - Rejects invalid/expired/used token.

### Auth-Only Routes

- `GET /dashboard`
  - Authenticated landing page shown after successful login.
  - Unauthenticated requests redirect to login page.

- `POST /logout`
  - Requires active authenticated session.
  - Invalidates current session and redirects to login route.

- `GET /profile`
  - Returns profile page with authenticated user data.
  - Required fields displayed: `name`, `email`.

- `PUT|POST /profile/password`
  - Accepts current password, new password, and confirmation.
  - Requires current-password verification.
  - On success: updates password and invalidates all other active sessions.

## Validation and Error Semantics

- Authentication failures return safe, non-enumerating messages.
- Reset request responses do not reveal account existence.
- Password policy violations return explicit validation errors without exposing sensitive internals.
- Current-password mismatch on profile change returns validation failure and no update.
- Sensitive values (passwords, reset tokens) must never be logged.

## Testable Contract Assertions

- Unauthenticated user cannot access `/dashboard` or `/profile`.
- Authenticated user can load `/dashboard` and `/profile`.
- `/logout` invalidates session and blocks subsequent protected access.
- Reset token cannot be reused after successful password reset.
- Profile password change with valid current password succeeds; invalid current password fails.
- Successful profile password change invalidates other active sessions for same user.
