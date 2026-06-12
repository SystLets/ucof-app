# Data Model: User Provisioning Script for Login

## Entities

### UserAccount (existing — extended by provisioning)

- **Purpose**: Represents a login identity that can be created or reactivated by the provisioning command.
- **Existing Key Fields**:
  - `id`
  - `name`
  - `email` (unique)
  - `password` (hashed)
  - `status` (`active`, `locked`, `disabled`)
  - `failed_signin_attempts`
  - `last_signin_at` (nullable)
  - `created_at`, `updated_at`
- **Provisioning-Extended Fields**:
  - `provisioned_at` (datetime, nullable) — timestamp of last provisioning execution
  - `provisioned_from` (string, nullable) — hostname of container that executed provisioning
- **Validation Rules**:
  - `email` must be unique for new accounts (duplicate active email triggers rejection).
  - `name` must be non-empty.
  - `password` must satisfy `PasswordPolicyRule` (12+ chars, upper, lower, digit).
  - `status` on creation is always `active`.
  - On reactivation: existing inactive/disabled account status set back to `active`; password updated.

### ProvisioningRequest (value object — command input)

- **Purpose**: Represents the validated operator input passed to the provisioning command.
- **Key Fields**:
  - `name` (string, required)
  - `email` (string, required, valid email format)
  - `password` (string, required, policy-compliant)
  - `executed_at` (datetime, auto-captured)
  - `executed_from` (string, auto-captured — container hostname)
- **Validation Rules**:
  - All three operator fields must be present and non-empty.
  - `email` must pass email format validation.
  - `password` must satisfy shared `PasswordPolicyRule`.

### ProvisioningResult (value object — command output)

- **Purpose**: Represents the outcome returned and displayed to the operator after execution.
- **Key Fields**:
  - `outcome` (`created`, `reactivated`, `rejected_duplicate`, `rejected_invalid`)
  - `message` (non-sensitive operator-facing string)
  - `exit_code` (`0` for success, `1` for failure)
- **Validation Rules**:
  - `message` must never contain the supplied password.
  - `message` must not disclose internal stack traces or query details.

## State Transitions

### UserAccount Status During Provisioning

- `(none)` → `active`: new user created via provisioning.
- `inactive` → `active`: user reactivated via provisioning, password updated.
- `disabled` → `active`: user reactivated via provisioning, password updated.
- `active` → `active`: rejected; no change applied.
- `locked` → not affected by provisioning (treated as effectively active for duplicate detection).

## Storage Notes

- Unique index on `UserAccount.email` (already required by auth foundation).
- `provisioned_at` and `provisioned_from` fields are nullable additions to the existing users collection.
- No new MongoDB collection required.
