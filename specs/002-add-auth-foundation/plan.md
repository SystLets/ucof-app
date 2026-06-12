# Implementation Plan: Authorization and Access Foundation

**Branch**: `002-add-auth-foundation` | **Date**: 2026-06-11 | **Spec**: `/Users/eduardoluz/dev/ucof-app/specs/002-add-auth-foundation/spec.md`
**Input**: Feature specification from `/Users/eduardoluz/dev/ucof-app/specs/002-add-auth-foundation/spec.md`

## Summary

Deliver an authentication and authorization foundation for the Laravel monolith with MongoDB-backed identity data, including sign-in, password reset, authenticated dashboard entry, route protection, and operational user bootstrap script support in `deploy/`. The implementation keeps v1 authorization intentionally minimal by enforcing authenticated vs unauthenticated access with an initial role field for future expansion.

## Technical Context

**Language/Version**: PHP 8.3, Laravel 12.x  
**Primary Dependencies**: `laravel/framework`, `livewire/livewire`, `mongodb/laravel-mongodb`, PHPUnit 11  
**Storage**: MongoDB 7 (containerized)  
**Testing**: Laravel test runner (`php artisan test`), PHPUnit feature/unit tests  
**Target Platform**: Docker Compose local-dev/staging/production runtime  
**Project Type**: Monolithic web application (Laravel + Blade + Livewire)  
**Performance Goals**: Sign-in to dashboard under 30 seconds for acceptance scenarios; password reset response flow remains interactive under normal local load  
**Constraints**: Docker-first execution; migration/initialization must be idempotent; secure failure messaging; no host-installed PHP/Mongo requirements  
**Scale/Scope**: Initial auth foundation for one app module, one identity store, and one operator bootstrap flow

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- **I. Test-Driven Development (NON-NEGOTIABLE)**: PASS. Plan includes feature tests for sign-in, reset flow, dashboard protection, and provisioning script behavior before implementation completion.
- **II. Cloud-Native / Docker-First**: PASS. All auth operations, migrations, and script execution remain container-first through compose/deploy scripts.
- **III. Modular Architecture**: PASS (within current monolith boundaries). Auth is scoped as a dedicated domain slice (entities + routes + scripts + tests) with explicit contracts.
- **IV. Frontend Monolith**: PASS. Sign-in, reset, and dashboard pages are implemented in the existing single Laravel frontend.
- **V. Open Source Standards**: PASS. Contracts, quickstart, and docs updates are included in planned artifacts.

No constitutional violations identified.

## Project Structure

### Documentation (this feature)

```text
specs/002-add-auth-foundation/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   └── auth-interfaces.md
└── tasks.md
```

### Source Code (repository root)

```text
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Livewire/
├── Models/
└── Services/

resources/
└── views/
    ├── auth/
    └── dashboard/

routes/
└── web.php

deploy/
├── scripts/
│   ├── local-dev/
│   ├── staging/
│   └── production/
└── specs/

docker/
└── mongodb/

tests/
├── Feature/
│   └── Auth/
└── Unit/
```

**Structure Decision**: Keep the current Laravel monolith structure and add auth-focused slices under existing app/routes/resources/tests/deploy directories; avoid introducing new top-level applications or microservices.

## Phase 0: Research Focus

- Determine MongoDB-compatible identity modeling patterns for Laravel auth and reset tokens.
- Select secure password reset token lifecycle (creation, expiry, invalidation) suited to MongoDB collections.
- Define safe operational pattern for deploy-time user provisioning script (idempotent, non-leaky errors).
- Confirm route-level authorization baseline for v1 (`guest` vs `auth`) plus role field persistence for future policy expansion.

## Phase 1: Design Focus

- Define identity entities and validation/state transitions.
- Define auth interface contracts for UI routes and provisioning command/script.
- Prepare quickstart for migration, seed/provision, and auth verification flow in containers.
- Update agent context with only new tech decisions (none expected beyond current stack).

## Post-Design Constitution Check

- **TDD**: PASS. Design artifacts map each user story to testable outcomes.
- **Docker-first**: PASS. Provisioning and migration are executable through deploy/container scripts.
- **Modularity**: PASS. Auth domain entities and contracts are scoped and isolated.
- **Frontend monolith**: PASS. Pages remain in one Laravel frontend.
- **Open-source quality**: PASS. Contract + quickstart artifacts included.

No additional complexity justification needed.

## Complexity Tracking

No constitutional violations requiring justification.
