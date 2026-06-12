# Research: Authorization and Access Foundation

## Decision 1: Store identity data in dedicated MongoDB collections

- **Decision**: Use dedicated collections for user accounts and password reset requests, with explicit uniqueness constraints on login identity.
- **Rationale**: Aligns with FR-009 and FR-010, keeps auth data model explicit, and supports safe script-based provisioning.
- **Alternatives considered**:
  - Reuse a single mixed collection for all auth artifacts: rejected due to weak lifecycle separation.
  - Store reset requests outside database: rejected because persistence and expiration tracking are required.

## Decision 2: Enforce v1 route authorization as guest/auth boundary with role persistence

- **Decision**: Enforce access control at route level for unauthenticated vs authenticated users, while persisting a minimal role field (`user`, `operator`) for future expansion.
- **Rationale**: Delivers immediate protection for dashboard/authenticated routes while avoiding over-engineering before policy matrices are required.
- **Alternatives considered**:
  - Authentication-only without role field: rejected because provisioning operations need operator intent and future authorization path.
  - Full RBAC matrix in v1: rejected due to unnecessary complexity for current feature scope.

## Decision 3: Password reset token lifecycle is single-use, time-bound, and non-enumerating

- **Decision**: Issue reset requests as single-use tokens with explicit expiry, invalidate on use, and return generic responses for unknown identities.
- **Rationale**: Meets FR-004/005/006/013 and addresses edge cases for enumeration and replay.
- **Alternatives considered**:
  - Multi-use tokens until expiry: rejected due to replay risk.
  - Explicit unknown-account error responses: rejected due to account enumeration leakage.

## Decision 4: Deploy-time user provisioning script runs as container-first command

- **Decision**: Provide deploy script(s) in `deploy/scripts/*` that execute user provisioning within compose-managed runtime context.
- **Rationale**: Satisfies FR-011/012 and constitution Docker-first principle; avoids host tooling drift.
- **Alternatives considered**:
  - Manual DB shell inserts: rejected due to inconsistency and weak validation.
  - Provisioning only through UI admin page: rejected because initial bootstrap requires pre-auth operational path.

## Decision 5: Migration/initialization must be idempotent

- **Decision**: Identity collection creation and bootstrap checks are explicitly idempotent and safe for repeated execution.
- **Rationale**: Supports clean startup, repeated deployment workflows, and edge case requirement for already-initialized environments.
- **Alternatives considered**:
  - Fail-fast on existing collections: rejected due to operational fragility.
  - Drop/recreate collections on bootstrap: rejected due to destructive risk.
