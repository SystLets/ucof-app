# Data Model: Authentication Pages and Profile

## Entities

### UserAccount

- **Purpose**: Represents an authenticated identity that can access landing/profile features.
- **Key Fields**:
  - `id`
  - `name`
  - `email` (unique)
  - `password_hash`
  - `status` (`active`, `locked`, `disabled`)
  - `failed_signin_attempts`
  - `last_signin_at` (nullable)
  - `created_at`, `updated_at`
- **Validation Rules**:
  - `email` must be unique and valid format.
  - `name` must be non-empty for profile display.
  - `password_hash` must never store plaintext.
  - `status` must be in supported enum values.
- **Relationships**:
  - One-to-many with `AuthSession`.
  - One-to-many with `PasswordResetRequest`.

### AuthSession

- **Purpose**: Represents authenticated browser/session state for protected routes.
- **Key Fields**:
  - `id`
  - `user_account_id`
  - `session_identifier`
  - `created_at`
  - `invalidated_at` (nullable)
- **Validation Rules**:
  - Session belongs to exactly one user.
  - Invalidated session cannot authorize protected routes.
- **Relationships**:
  - Many-to-one to `UserAccount`.

### PasswordResetRequest

- **Purpose**: Tracks reset lifecycle from request through completion.
- **Key Fields**:
  - `id`
  - `user_account_id`
  - `token_hash`
  - `issued_at`
  - `expires_at`
  - `used_at` (nullable)
  - `request_ip` (nullable)
- **Validation Rules**:
  - Token is single-use.
  - Request invalid when `now > expires_at`.
  - Request invalid when `used_at` is set.
- **Relationships**:
  - Many-to-one to `UserAccount`.

### PasswordChangeRequest

- **Purpose**: Represents authenticated password update intent from profile.
- **Key Fields**:
  - `user_account_id`
  - `current_password`
  - `new_password`
  - `new_password_confirmation`
  - `requested_at`
- **Validation Rules**:
  - `current_password` must match active user credential.
  - `new_password` must satisfy password policy.
  - `new_password_confirmation` must match `new_password`.

## State Transitions

### Authentication Session Lifecycle

- `active` -> `invalidated`: explicit logout.
- `active` -> `invalidated`: password change by same user on another device/session.
- `active` remains active for current session after successful password change.

### Password Reset Lifecycle

- `issued` -> `used`: valid completion.
- `issued` -> `expired`: current time exceeds `expires_at`.
- `used` and `expired` are terminal states.

### UserAccount Access State

- `active` -> `locked`: threshold of failed sign-in attempts reached.
- `locked` -> `active`: lock window elapsed or operator action.
- `active` -> `disabled`: administrative action (out of primary scope but compatible).

## Indexing and Storage Notes

- Unique index on `UserAccount.email`.
- Index on `PasswordResetRequest.token_hash` for lookup.
- Index on `PasswordResetRequest.expires_at` for expiration checks.
- Index on `AuthSession.user_account_id` and `session_identifier` for invalidation operations.
