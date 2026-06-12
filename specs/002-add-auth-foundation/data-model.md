# Data Model: Authorization and Access Foundation

## Entities

### UserAccount

- **Purpose**: Represents an application identity used for authentication and access control.
- **Key Fields**:
  - `id`
  - `email` (unique login identity)
  - `password_hash`
  - `role` (`user` or `operator` in v1)
  - `status` (`active`, `locked`, `disabled`)
  - `failed_signin_attempts`
  - `lock_expires_at` (nullable)
  - `last_signin_at` (nullable)
  - `created_at`, `updated_at`
- **Validation Rules**:
  - `email` must be unique and valid format.
  - `password_hash` must never store plaintext credentials.
  - `role` must be one of the allowed role values.
  - `status` transitions must be controlled by auth workflows.
- **Relationships**:
  - One-to-many with `CredentialResetRequest`.
  - One-to-many with `AuthSession`.

### CredentialResetRequest

- **Purpose**: Tracks reset requests and token lifecycle.
- **Key Fields**:
  - `id`
  - `user_account_id`
  - `token_hash`
  - `issued_at`
  - `expires_at`
  - `used_at` (nullable)
  - `request_ip` (nullable)
  - `created_at`
- **Validation Rules**:
  - Token must be single-use.
  - Request is invalid when `now > expires_at`.
  - Request is invalid when `used_at` is set.
- **Relationships**:
  - Many-to-one to `UserAccount`.

### AuthSession

- **Purpose**: Represents authenticated session state for route protection and sign-out invalidation.
- **Key Fields**:
  - `id`
  - `user_account_id`
  - `session_identifier`
  - `created_at`
  - `invalidated_at` (nullable)
- **Validation Rules**:
  - Session must belong to exactly one user.
  - Invalidated sessions cannot be reused.
- **Relationships**:
  - Many-to-one to `UserAccount`.

### ProvisioningRequest (Operational)

- **Purpose**: Operator-provided input schema for deploy-time user creation command/script.
- **Key Fields**:
  - `email`
  - `initial_role`
  - `temporary_password`
  - `requested_by` (operator context)
  - `requested_at`
- **Validation Rules**:
  - Required fields must be present and non-empty.
  - Duplicate identities are rejected.
  - Password policy must be enforced.

## State Transitions

### UserAccount Status

- `active` -> `locked`: triggered by repeated invalid sign-in attempts.
- `locked` -> `active`: automatic unlock after lock duration or operator action.
- `active` -> `disabled`: operator/admin disable flow (future extension).

### CredentialResetRequest Lifecycle

- `issued` -> `used`: valid reset completion.
- `issued` -> `expired`: after expiration timestamp.
- `used` and `expired` are terminal states.

## Storage Notes

- Identity collections are created via migration/initialization path in MongoDB.
- Unique index on user identity (`email`) is mandatory.
- Supporting indexes for reset token lookup and expiration checks are required.
