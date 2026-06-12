# Implementation Plan: User Provisioning Script for Login

**Branch**: `004-add-user-seed` | **Date**: 2026-06-12 | **Spec**: `/Users/eduardoluz/dev/ucof-app/specs/004-add-user-seed/spec.md`
**Input**: Feature specification from `/Users/eduardoluz/dev/ucof-app/specs/004-add-user-seed/spec.md`

## Summary

Deliver a shared Artisan command (`ucof:provision-user`) backed by thin per-environment shell wrapper scripts under `deploy/scripts/<env>/add-user.sh` for local-dev, staging, and production. The command accepts `--name`, `--email`, and `--password` as required CLI arguments, validates them against the existing `PasswordPolicyRule`, creates a new active user or reactivates an inactive/disabled one, records execution timestamp and hostname, and emits safe non-sensitive feedback.

## Technical Context

**Language/Version**: PHP 8.3, Laravel 12.x  
**Primary Dependencies**: `laravel/framework`, `mongodb/laravel-mongodb`, PHPUnit 11  
**Storage**: MongoDB 7 (containerized) — `users` collection via existing `App\Models\User`  
**Testing**: Laravel test runner (`php artisan test`), PHPUnit feature/unit tests for provisioning command behaviour  
**Target Platform**: Docker Compose local-dev/staging/production runtime  
**Project Type**: Monolithic web application with Artisan CLI extension  
**Performance Goals**: Provisioning a single user completes within 5 minutes end-to-end (SC-001)  
**Constraints**: Docker-first execution; no interactive prompts; CLI arguments only; reuses existing `App\Rules\PasswordPolicyRule` and `App\Models\User`; no password echo in output  
**Scale/Scope**: One Artisan command + three thin shell wrapper scripts; single-user provisioning path per invocation

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- **I. Test-Driven Development (NON-NEGOTIABLE)**: PASS. Plan includes unit tests for validation logic and feature tests for create/reactivate/duplicate/invalid-input scenarios.
- **II. Cloud-Native / Docker-First**: PASS. All execution and testing goes through `docker compose exec app php artisan ucof:provision-user ...`; wrapper scripts delegate into containers.
- **III. Modular Architecture**: PASS. Provisioning is a self-contained Artisan command under `app/Console/Commands/` that depends only on existing auth-domain models and rules.
- **IV. Frontend as Monolith**: PASS. This is a CLI/backend feature with no frontend impact.
- **V. Open Source Standards**: PASS. Contract, quickstart, and safe output semantics included in planning artifacts.

No constitutional violations identified.

## Project Structure

### Documentation (this feature)

```text
specs/004-add-user-seed/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   └── provision-user-contract.md
└── tasks.md
```

### Source Code (repository root)

```text
app/
└── Console/
    └── Commands/
        └── ProvisionUser.php

deploy/
└── scripts/
    ├── local-dev/
    │   └── add-user.sh
    ├── staging/
    │   └── add-user.sh
    └── production/
        └── add-user.sh

tests/
├── Feature/
│   └── Provisioning/
│       └── ProvisionUserCommandTest.php
└── Unit/
    └── Provisioning/
        └── ProvisionUserValidationTest.php
```

**Structure Decision**: Single Artisan command in `app/Console/Commands/`; no new service class needed as provisioning logic is self-contained. Three thin shell wrappers in existing `deploy/scripts/<env>/` delegate to `docker compose exec app php artisan ucof:provision-user`.

## Phase 0: Research Focus

- Confirm correct reuse path for `App\Rules\PasswordPolicyRule` within Artisan command context.
- Verify `App\Models\User` supports `forceFill`/`save` for status and password updates without session side effects.
- Confirm `gethostname()` availability and stability inside PHP-FPM container for audit hostname capture.
- Establish safe output semantics: verify Artisan console methods emit no credential leakage.

## Phase 1: Design Focus

- Define `ProvisioningRequest` value object field set and `ProvisioningResult` outcome model.
- Define CLI contract: argument names, required flags, exit codes, and output format.
- Define wrapper script interface: how each `add-user.sh` passes arguments to the Artisan command via `docker compose exec`.
- Produce quickstart for manual provisioning and automated test validation in containers.
- Update agent context (no new technology additions — stack is unchanged).

## Post-Design Constitution Check

- **TDD**: PASS. Provisioning command tests cover create, reactivate, duplicate-active rejection, missing-argument rejection, and weak-password rejection.
- **Docker-first**: PASS. Wrapper scripts and quickstart are container-first only.
- **Modularity**: PASS. Command is isolated and depends only on existing shared domain objects.
- **Frontend monolith**: PASS. No frontend impact.
- **Open-source quality**: PASS. Contract and quickstart artifacts included.

No additional complexity justification required.

## Complexity Tracking

No constitutional violations requiring justification.
