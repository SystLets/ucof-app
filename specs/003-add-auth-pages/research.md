# Research: Authentication Pages and Profile

## Decision 1: Password change revokes all other active sessions

- **Decision**: When a user changes password from profile, invalidate all other active sessions and keep only the current session active.
- **Rationale**: Balances security and user continuity; reduces account-takeover risk while avoiding forced immediate logout of the current verified user.
- **Alternatives considered**:
  - Revoke all sessions including current: stronger but adds friction and breaks in-flow completion UX.
  - Keep all sessions unchanged: rejected due to unnecessary residual risk after credential rotation.

## Decision 2: Login and reset responses are non-enumerating

- **Decision**: Use generic failure/success messaging for invalid login and reset-request outcomes without indicating account existence.
- **Rationale**: Directly supports FR-013 and mitigates identity enumeration.
- **Alternatives considered**:
  - Detailed identity-specific errors: rejected due to leakage risk.

## Decision 3: Password reset tokens are single-use, hashed, and time-bound

- **Decision**: Persist hashed reset tokens with explicit expiration and used-at markers; reject expired, missing, or already-used tokens.
- **Rationale**: Covers FR-005/FR-006 and replay edge cases.
- **Alternatives considered**:
  - Multi-use tokens until expiration: rejected due to replay exposure.
  - Plain-text token persistence: rejected for security hygiene reasons.

## Decision 4: Route protection follows strict guest/auth middleware boundaries

- **Decision**: Login and reset pages/routes are guest-only; landing, profile, password change, and logout are auth-only.
- **Rationale**: Ensures deterministic user flow and direct support for FR-008/FR-009.
- **Alternatives considered**:
  - Controller-level ad hoc checks only: rejected due to weaker consistency and maintainability.

## Decision 5: Validation policy aligns reset and profile password-change flows

- **Decision**: Apply the same minimum password policy and confirmation rules to both reset completion and profile password change.
- **Rationale**: Reduces policy drift and keeps tests/UX consistent across credential update paths.
- **Alternatives considered**:
  - Different policies by flow: rejected because it creates unnecessary complexity and user confusion.
