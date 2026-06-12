# Implementation Plan: Authentication Pages and Profile

**Branch**: `003-add-auth-pages` | **Date**: 2026-06-12 | **Spec**: `/Users/eduardoluz/dev/ucof-app/specs/003-add-auth-pages/spec.md`
**Input**: Feature specification from `/Users/eduardoluz/dev/ucof-app/specs/003-add-auth-pages/spec.md`

## Summary

Deliver complete account-access UI and flow coverage for existing users: login page, password reset request and completion pages, authenticated landing page, logout route, and profile page (name/email display plus password change). The implementation uses Laravel + Livewire in the existing frontend monolith, MongoDB-backed identity/session data, and container-first execution for runtime and tests.

## Technical Context

**Language/Version**: PHP 8.3, Laravel 12.x  
**Primary Dependencies**: `laravel/framework`, `livewire/livewire`, `mongodb/laravel-mongodb`, PHPUnit 11  
**Storage**: MongoDB 7 (containerized)  
**Testing**: Laravel test runner (`php artisan test`), PHPUnit feature tests (auth/profile flows), contract-style route behavior assertions  
**Target Platform**: Docker Compose local-dev/staging/production runtime  
**Project Type**: Monolithic web application (Laravel + Blade + Livewire)  
**Performance Goals**: Valid users reach landing page within 30 seconds (SC-001); auth and profile interactions remain responsive for normal local-dev usage  
**Constraints**: Docker-first operations, safe non-enumerating auth errors, single-use reset tokens, authenticated-only access to landing/profile, current-password verification on password change  
**Scale/Scope**: One application auth surface with five user-facing pages/routes and associated security flows for existing accounts

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- **I. Test-Driven Development (NON-NEGOTIABLE)**: PASS. Plan includes feature tests for login/logout protection, reset token lifecycle, landing access control, and profile password-change validation.
- **II. Cloud-Native / Docker-First**: PASS. All app runtime and test execution remains via Docker Compose (`docker compose exec app ...`).
- **III. Modular Architecture**: PASS. Auth/profile capability is scoped as a dedicated domain slice within existing monolith boundaries and explicit route/service contracts.
- **IV. Frontend as Monolith**: PASS. All user-facing auth/profile pages live in the existing single Laravel frontend.
- **V. Open Source Standards**: PASS. Planning artifacts include explicit contracts, quickstart, and measurable outcomes aligned with transparent contributor workflows.

No constitutional violations identified.

## Project Structure

### Documentation (this feature)

```text
/Users/eduardoluz/dev/ucof-app/specs/003-add-auth-pages/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   └── auth-pages-interfaces.md
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
    ├── profile/
    └── dashboard/

routes/
└── web.php

tests/
├── Feature/
│   └── Auth/
└── Unit/
```

**Structure Decision**: Keep the current Laravel monolith and add feature-specific slices under existing `app/`, `resources/views/`, `routes/`, and `tests/` directories to preserve architectural consistency and avoid new top-level applications.

## Phase 0: Research Focus

- Confirm secure default behavior for password-change session handling across multiple devices.
- Define anti-enumeration and anti-replay patterns for login and password reset interactions.
- Select route and UX flow conventions for post-login landing and profile update confirmations in Laravel monolith.
- Confirm token invalidation and expiry behavior for reset flows in MongoDB-backed storage.

## Phase 1: Design Focus

- Define entities and lifecycle transitions for user sessions, reset requests, and password changes.
- Define interface contracts for login/logout/reset/landing/profile flows including access control semantics.
- Produce container-first quickstart for manual validation and test execution of all acceptance flows.
- Update agent context with current feature technology context and no unnecessary stack drift.

## Post-Design Constitution Check

- **TDD**: PASS. Design maps each user story to independently testable outcomes and route protection checks.
- **Docker-first**: PASS. Quickstart and planned validation steps are container-first only.
- **Modularity**: PASS. Contracts keep auth/profile behavior bounded and explicit.
- **Frontend monolith**: PASS. Pages are planned as one cohesive frontend runtime.
- **Open-source quality**: PASS. Artifacts provide contributor-facing behavior contracts and operational guidance.

No additional complexity justification required.

## Complexity Tracking

No constitutional violations requiring justification.
